<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventarisResource;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventarisApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $inventaris = Inventaris::with(['barang', 'ruangan'])->get(); // Menggunakan eager loading

        return response()->json([
            'success' => true,
            'data' => InventarisResource::collection($inventaris), // Menggunakan resource
        ], 200);
    }

    /**
     * Update kondisi_barang of a specific inventaris record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateKondisi(Request $request, $id)
    {
        Log::info('Menerima request update kondisi', [
            'id' => $id,
            'kondisi_barang' => $request->kondisi_barang,
        ]);

        $request->validate([
            'kondisi_barang' => 'required|in:lengkap,tidak_lengkap,rusak',
        ]);

        $inventaris = Inventaris::find($id);

        if (! $inventaris) {
            Log::error("Inventaris tidak ditemukan untuk ID: $id");

            return response()->json([
                'success' => false,
                'message' => 'Inventaris tidak ditemukan.',
            ], 404);
        }

        $inventaris->update(['kondisi_barang' => $request->kondisi_barang]);

        Log::info('Berhasil update kondisi barang', ['id' => $id, 'kondisi_barang' => $request->kondisi_barang]);

        return response()->json([
            'success' => true,
            'message' => 'Kondisi barang berhasil diperbarui.',
            'data' => new InventarisResource($inventaris),
        ], 200);
    }
}
