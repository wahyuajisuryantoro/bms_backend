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
use Illuminate\Support\Str;

class ProfileController extends Controller
{
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
            $userDetailArray = $userDetail->toArray();
            if ($userDetail->foto) {
                $userDetailArray['photo_url'] = asset('storage/foto_user/' . $userDetail->foto);
            } else {
                $userDetailArray['photo_url'] = null;
            }

            return response()->json([
                'status' => true,
                'message' => 'Data detail pengguna berhasil diambil',
                'data' => [
                    'user' => $user,
                    'detail' => $userDetailArray,
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
        if ($request->hasFile('foto')) {
            if ($userDetail->foto && Storage::disk('public')->exists('foto_user/' . $userDetail->foto)) {
                Storage::disk('public')->delete('foto_user/' . $userDetail->foto);
            }
            $file = $request->file('foto');
            $randomString = Str::random(15);
            $filename = 'foto_user_' . $userDetail->id . '_' . $randomString . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('foto_user', $filename, 'public');
            $userDetail->foto = $filename;
        }

        $userDetail->save();
        DB::commit();

        $user->refresh();
        $userDetail->load(['province', 'regency', 'district', 'village']);

        $userDetailArray = $userDetail->toArray();
        if ($userDetail->foto) {
            $photoUrl = asset('storage/foto_user/' . $userDetail->foto);
            $userDetailArray['photo_url'] = $photoUrl;
        } else {
            $userDetailArray['photo_url'] = null;
        }

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'user' => $user,
                'detail' => $userDetailArray
            ]
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}
}