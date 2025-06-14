<?php

namespace App\Http\Controllers\Landing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    public function index (){
        return view('landing.content');
    }

    public function hapusAkun(){
        return view('landing.hapus_akun');
    }

     public function privasi(){
        return view('landing.privasi-kebijakan');
    }

    public function storePesan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'subject.required' => 'Subjek wajib diisi',
            'subject.max' => 'Subjek maksimal 255 karakter',
            'message.required' => 'Pesan wajib diisi',
            'message.max' => 'Pesan maksimal 1000 karakter',
        ]);

        try {
            // Data pesan untuk email
            $pesanData = [
                'nama' => $request->name,
                'email' => $request->email,
                'judul' => $request->subject,
                'isi' => $request->message,
                'created_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Kirim email langsung tanpa menyimpan ke database
            $this->sendPesanEmail($pesanData);
            
            return redirect()->back()->with('success', 'Pesan berhasil dikirim! Tim kami akan menghubungi Anda segera.');
        } catch (\Exception $e) {
            Log::error('Error sending contact message: ' . $e->getMessage(), [
                'email' => $request->email,
                'name' => $request->name,
                'subject' => $request->subject,
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi atau hubungi kami langsung.')
                ->withInput();
        }
    }

     protected function sendPesanEmail($pesanData)
    {
        // Ambil email admin dari .env
       $adminEmail = 'bursamobilsolo1@gmail.com';
        
        if (!$adminEmail) {
            Log::warning('Email admin tidak ditemukan di .env');
            throw new \Exception('Email admin tidak dikonfigurasi');
        }

        try {
            Mail::raw($this->buildEmailContent($pesanData), function ($message) use ($adminEmail, $pesanData) {
                $message->to($adminEmail)
                        ->subject('Pesan Baru dari User Web BMS: ' . $pesanData['judul'])
                        ->replyTo($pesanData['email'], $pesanData['nama']);
            });

        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function buildEmailContent($pesanData)
    {
        return "Terdapat pesan baru dari {$pesanData['nama']} ({$pesanData['email']}).\n\n" .
               "Subjek: {$pesanData['judul']}\n\n" .
               "Pesan:\n{$pesanData['isi']}\n\n" .
               "Waktu: {$pesanData['created_at']}";
    }

}
