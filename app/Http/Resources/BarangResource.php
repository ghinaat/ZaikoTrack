<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
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
            'id_barang' => $this->id_barang,
            'nama_barang' => $this->nama_barang,
            'merek' => $this->merek,
            'stok_barang' => $this->stok_barang,
            'kode_barang' => $this->kode_barang,
            'qrcode_image' => url('storage/qrcode/'.$this->qrcode_image),
            'jenis_barang' => $this->whenLoaded('jenisbarang', function () {
                return [
                    'id_jenis_barang' => $this->jenisbarang->id_jenis_barang,
                    'nama_jenis_barang' => $this->jenisbarang->nama_jenis_barang,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
