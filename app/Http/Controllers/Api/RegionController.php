<?php

namespace App\Http\Controllers\Api;

use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    public function getProvinces()
    {
        try {
            $provinces = Province::orderBy('name')->get();

            return response()->json([
                'status' => true,
                'message' => 'Data provinsi berhasil diambil',
                'data' => $provinces
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getRegencies($provinceId)
    {
        try {
            $regencies = Regency::where('province_id', $provinceId)
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data kota/kabupaten berhasil diambil',
                'data' => $regencies
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getDistricts($regencyId)
    {
        try {
            $districts = District::where('regency_id', $regencyId)
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data kecamatan berhasil diambil',
                'data' => $districts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getVillages($districtId)
    {
        try {
            $villages = Village::where('district_id', $districtId)
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data kelurahan/desa berhasil diambil',
                'data' => $villages
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
