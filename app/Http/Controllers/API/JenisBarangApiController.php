<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisBarang;

class JenisBarangApiController extends Controller
{
    public function index()
    {
        $jenisBarang = JenisBarang::all();

        return response()->json([
            'success' => true,
            'data' => $jenisBarang,
        ], 200);
    }
}
