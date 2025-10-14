<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Mail\VerifyEmail;
use App\Models\UserDetail;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak terdaftar.'
            ], 401);
        }
        if (is_null($user->email_verified_at)) {
            $isPending = false;
            $email = $user->email;
            foreach (Cache::getPrefix() as $key) {
                if (strpos($key, 'pending_user_') === 0) {
                    $data = Cache::get($key);
                    if ($data && isset($data['email']) && $data['email'] === $email) {
                        $isPending = true;
                        break;
                    }
                }
            }

            if ($isPending) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email belum diverifikasi. Silakan cek email Anda untuk link verifikasi.',
                    'verification_required' => true,
                    'email' => $email
                ], 403);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.',
                    'verification_required' => true,
                    'email' => $email
                ], 403);
            }
        }

        if (!\Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah.'
            ], 401);
        }

        if ($user->role !== 'user') {
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan Akses Data'
            ], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;
        $userDetail = UserDetail::where('user_id', $user->id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'no_wa' => $userDetail ? $userDetail->no_wa : null,
                'email_verified' => !is_null($user->email_verified_at)
            ],
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_wa' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $verificationToken = Str::random(64);

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_wa' => $request->no_wa,
                'created_at' => now()->toDateTimeString(),
                'role' => 'user',
            ];

            Cache::put('pending_user_' . $verificationToken, $userData, now()->addHours(24));

            $this->sendVerificationEmail($request->email, $verificationToken);

            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil. Silakan cek email Anda untuk verifikasi.',
                'email' => $request->email
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error during registration: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
                'new_password_confirmation' => 'required|string|min:8',
            ], [
                'current_password.required' => 'Password lama wajib diisi.',
                'new_password.required' => 'Password baru wajib diisi.',
                'new_password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
                'new_password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password lama tidak sesuai.'
                ], 400);
            }
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password baru tidak boleh sama dengan password lama.'
                ], 400);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diperbarui.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyToken($token)
    {
        try {
            $userData = Cache::get('pending_user_' . $token);

            if (!$userData) {
                return view('verification.failed', [
                    'message' => 'Token verifikasi tidak valid atau sudah kadaluarsa'
                ]);
            }

            if (User::where('email', $userData['email'])->exists()) {
                return view('verification.failed', [
                    'message' => 'Email sudah terdaftar sebelumnya'
                ]);
            }

            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role' => $userData['role'],
                'email_verified_at' => now(),
            ]);
            UserDetail::create([
                'user_id' => $user->id,
                'no_wa' => $userData['no_wa'],
            ]);
            Cache::forget('pending_user_' . $token);
            return view('verification.success', [
                'message' => 'Email berhasil diverifikasi. Akun Anda telah dibuat.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error during verification: ' . $e->getMessage());
            return view('verification.failed', [
                'message' => 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage()
            ]);
        }
    }

    public function verify(Request $request)
    {
        return response()->json([
            'status' => false,
            'message' => 'Metode verifikasi ini sudah tidak digunakan. Silakan gunakan link verifikasi yang dikirim ke email Anda.'
        ], 410);
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email sudah diverifikasi sebelumnya.'
                ], 400);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email sudah terdaftar tapi belum diverifikasi. Silakan lakukan registrasi ulang.'
                ], 400);
            }
        }
        $foundPendingUser = false;
        $newToken = Str::random(64);

        foreach (Cache::getPrefix() as $key) {
            if (strpos($key, 'pending_user_') === 0) {
                $data = Cache::get($key);
                if ($data && isset($data['email']) && $data['email'] === $request->email) {
                    $foundPendingUser = true;

                    Cache::forget($key);

                    Cache::put('pending_user_' . $newToken, $data, now()->addHours(24));

                    $this->sendVerificationEmail($request->email, $newToken);

                    return response()->json([
                        'status' => true,
                        'message' => 'Link verifikasi telah dikirim ulang ke email Anda.'
                    ]);
                }
            }
        }
        if (!$foundPendingUser) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ditemukan pendaftaran yang belum diverifikasi dengan email ini.'
            ], 404);
        }
    }

    public function resendVerificationSimple(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Email sudah terdaftar. Jika belum terverifikasi, harap daftar dengan email lain.'
            ], 400);
        }
        return response()->json([
            'status' => false,
            'message' => 'Silakan daftar ulang untuk mendapatkan link verifikasi baru.'
        ], 404);
    }

    protected function sendVerificationEmail($email, $token)
    {
        $verificationUrl = $this->generateVerificationUrl($token);
        try {
            Mail::to($email)->send(new VerifyEmail(['email' => $email], $verificationUrl));
            \Log::info('Verification email sent successfully to: ' . $email);
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function generateVerificationUrl($token)
    {
        $url = url('api/email/verify-token/' . $token);
        return $url;
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak terdaftar.'
            ], 404);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'status' => false,
                'message' => 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.'
            ], 403);
        }

        try {
            $resetToken = Str::random(64);

            Cache::put('password_reset_' . $resetToken, [
                'email' => $request->email,
                'created_at' => now()->toDateTimeString()
            ], now()->addHour());

            $this->sendResetPasswordEmail($request->email, $resetToken);

            return response()->json([
                'status' => true,
                'message' => 'Link reset password telah dikirim ke email Anda. Link berlaku selama 1 jam.',
                'email' => $request->email
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error during forgot password: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyResetToken($token)
    {
        try {
            $resetData = Cache::get('password_reset_' . $token);

            if (!$resetData) {
                return view('password.failed', [
                    'message' => 'Token reset password tidak valid atau sudah kadaluarsa. Link hanya berlaku selama 1 jam.'
                ]);
            }
            return view('reset-password.reset-password-form', [
                'token' => $token,
                'email' => $resetData['email']
            ]);

        } catch (\Exception $e) {
            \Log::error('Error verifying reset token: ' . $e->getMessage());
            return view('password.failed', [
                'message' => 'Terjadi kesalahan saat memverifikasi token.'
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'token.required' => 'Token reset password wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $resetData = Cache::get('password_reset_' . $request->token);

            if (!$resetData) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token reset password tidak valid atau sudah kadaluarsa.'
                ], 400);
            }

            $user = User::where('email', $resetData['email'])->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password baru tidak boleh sama dengan password lama.'
                ], 400);
            }
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            Cache::forget('password_reset_' . $request->token);
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil direset. Silakan login dengan password baru Anda.'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error during reset password: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function sendResetPasswordEmail($email, $token)
    {
        $resetUrl = $this->generateResetPasswordUrl($token);

        try {
            Mail::to($email)->send(new ResetPassword($email, $resetUrl));
            \Log::info('Reset password email sent successfully to: ' . $email);
        } catch (\Exception $e) {
            \Log::error('Failed to send reset password email: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function generateResetPasswordUrl($token)
    {
        return url('api/password/reset-token/' . $token);
    }



    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat logout: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkRegistrationStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json([
                'status' => true,
                'registered' => true,
                'verified' => !is_null($user->email_verified_at)
            ]);
        }

        $isPending = false;

        foreach (Cache::getPrefix() as $key) {
            if (strpos($key, 'pending_user_') === 0) {
                $data = Cache::get($key);
                if ($data && isset($data['email']) && $data['email'] === $request->email) {
                    $isPending = true;
                    break;
                }
            }
        }

        return response()->json([
            'status' => true,
            'registered' => false,
            'pending' => $isPending
        ]);
    }

}