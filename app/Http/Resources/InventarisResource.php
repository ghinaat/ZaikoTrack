<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventarisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id_inventaris' => $this->id_inventaris,
            'barang' => new BarangResource($this->whenLoaded('barang')), // Menggunakan BarangResource
            'ruangan' => $this->whenLoaded('ruangan', function () {
                return [
                    'id_ruangan' => $this->ruangan->id_ruangan,
                    'nama_ruangan' => $this->ruangan->nama_ruangan,
                ];
            }),
            'jumlah_barang' => $this->jumlah_barang,
            'kondisi_barang' => $this->kondisi_barang,
            'ket_barang' => $this->ket_barang,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
