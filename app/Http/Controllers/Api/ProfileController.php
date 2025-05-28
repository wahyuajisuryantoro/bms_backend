<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDetail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Update user profile and details
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'province_id' => 'nullable|string|exists:provinces,id',
                'regency_id' => 'nullable|string|exists:regencies,id',
                'district_id' => 'nullable|string|exists:districts,id',
                'village_id' => 'nullable|string|exists:villages,id',
                'dusun' => 'nullable|string|max:100',
                'alamat_lengkap' => 'nullable|string',
                'kode_pos' => 'nullable|string|max:10',
                'no_wa' => 'nullable|string|max:20',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $user = $request->user();
            
            if ($request->has('name') && $request->name) {
                $user->name = $request->name;
                $user->save();
            }

            $userDetail = UserDetail::firstOrCreate(
                ['user_id' => $user->id],
                ['user_id' => $user->id]
            );

            $detailFields = [
                'province_id' => $request->province_id,
                'regency_id' => $request->regency_id,
                'district_id' => $request->district_id,
                'village_id' => $request->village_id,
                'dusun' => $request->dusun,
                'alamat_lengkap' => $request->alamat_lengkap,
                'kode_pos' => $request->kode_pos,
                'no_wa' => $request->no_wa,
            ];

            foreach ($detailFields as $field => $value) {
                if ($request->has($field)) {
                    $userDetail->$field = $value ?: null;
                }
            }

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $photoPath = storage_path('app/public/photos');
                if (!File::exists($photoPath)) {
                    File::makeDirectory($photoPath, 0755, true);
                }

                if ($userDetail->foto && Storage::exists('public/photos/' . $userDetail->foto)) {
                    Storage::delete('public/photos/' . $userDetail->foto);
                }

                $file = $request->file('foto');
                $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('public/photos', $filename);
                
                if (Storage::exists($path)) {
                    $userDetail->foto = $filename;
                    \Log::info('Photo uploaded successfully', [
                        'filename' => $filename,
                        'path' => $path,
                        'full_path' => storage_path('app/' . $path)
                    ]);
                } else {
                    throw new \Exception('Failed to store photo file');
                }
            }

            $userDetail->save();

            DB::commit();

            // Reload with relations
            $user->refresh();
            $userDetail->load(['province', 'regency', 'district', 'village']);

            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => [
                    'user' => $user,
                    'detail' => $userDetail
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Profile update error', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile with details
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user();
            $userDetail = UserDetail::with(['province', 'regency', 'district', 'village'])
                ->where('user_id', $user->id)
                ->first();

            if (!$userDetail) {
                $userDetail = UserDetail::create(['user_id' => $user->id]);
                $userDetail->load(['province', 'regency', 'district', 'village']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data detail pengguna berhasil diambil',
                'data' => [
                    'user' => $user,
                    'detail' => $userDetail,
                    'address' => [
                        'full' => $userDetail->full_address,
                        'short' => $userDetail->short_address
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}