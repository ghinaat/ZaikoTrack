<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Inventaris;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'id_users' => '1',
            'name' => 'Kepala Program',
            'email' => 'kaprog@kaprog.com',
            'password' => '12345678',
            'level' => 'kaprog',
        ]);

        User::create([
            'id_users' => '2',
            'name' => 'Teknisi',
            'email' => 'teknisi@teknisi.com',
            'password' => '12345678',
            'level' => 'teknisi',
        ]);

        User::create([
            'id_users' => '3',
            'name' => 'Kepala Bengkel',
            'email' => 'kabeng@kabeng.com',
            'password' => '12345678',
            'level' => 'kabeng',
        ]);

        JenisBarang::create([
            'id_jenis_barang' => '1',
            'nama_jenis_barang' => 'Perlengkapan Kelas',
        ]);

        JenisBarang::create([
            'id_jenis_barang' => '2',
            'nama_jenis_barang' => 'Alat Praktik',
        ]);

        JenisBarang::create([
            'id_jenis_barang' => '3',
            'nama_jenis_barang' => 'Bahan Praktik',
        ]);



        Ruangan::create([
            'nama_ruangan' => 'Lab 1',
        ]);

        Ruangan::create([
            'nama_ruangan' => 'Ruang Guru',
        ]);

        Ruangan::create([
            'nama_ruangan' => 'Ruang Penyimpanan',
        ]);

        Barang::create([
            'id_barang' => '1',
            'nama_barang' => 'Router',
            'merek' => 'Mikrotik',
            'stok_barang' => '5',
            'id_jenis_barang' => '2',
        ]);

        Barang::create([
            'id_barang' => '2',
            'nama_barang' => 'Router',
            'merek' => 'Mikrotik',
            'stok_barang' => '20',
            'id_jenis_barang' => '2',
        ]);

        Barang::create([
            'id_barang' => '3',
            'nama_barang' => 'Kabel UTP',
            'merek' => 'Belden',
            'stok_barang' => '7',
            'id_jenis_barang' => '3',
        ]);

        Barang::create([
            'id_barang' => '4',
            'nama_barang' => 'Meja Siswa',
            'merek' => 'Informa',
            'stok_barang' => '60',
            'id_jenis_barang' => '1',
        ]);

        Barang::create([
            'id_barang' => '5',
            'nama_barang' => 'Connector rj45',
            'merek' => 'Belden',
            'stok_barang' => '15',
            'id_jenis_barang' => '3',
        ]);

        Barang::create([
            'id_barang' => '6',
            'nama_barang' => 'Connector Fiber Optik',
            'merek' => 'Netlink',
            'stok_barang' => '10',
            'id_jenis_barang' => '3',
        ]);

        Inventaris::create([
            'id_inventaris' => '11',
            'id_barang' => '5',
            'id_ruangan' => '1',
            'jumlah_barang' => '10',
            'kondisi_barang' => 'lengkap',
            'ket_barang' => ''
        ]);

        Inventaris::create([
            'id_inventaris' => '12',
            'id_barang' => '6',
            'id_ruangan' => '3',
            'jumlah_barang' => '10',
            'kondisi_barang' => 'lengkap',
            'ket_barang' => ''
        ]);
        \App\Models\Pembelian::factory(10)->create();
        \App\Models\DetailPembelian::factory(20)->create();
        \App\Models\Inventaris::factory(10)->create();
          

    }
}