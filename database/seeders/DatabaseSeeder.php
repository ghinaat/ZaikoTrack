<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\Ruangan;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Karyawan;
use App\Models\EmailConfiguration;
use Illuminate\Database\Seeder;
use App\Models\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;




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
            'name' => '-',
            'email' => '-',
            'password' => '12345678',
            'level' => 'siswa',
        ]);
        
        User::create([
            'id_users' => '2',
            'name' => 'Kepala Program',
            'email' => 'kaprog@kaprog.com',
            'password' => '12345678',
            'level' => 'kaprog',
        ]);

        User::create([
            'id_users' => '3',
            'name' => 'Teknisi',
            'email' => 'teknisi@teknisi.com',
            'password' => '12345678',
            'level' => 'teknisi',
        ]);

        User::create([
            'id_users' => '4',
            'name' => 'Kepala Bengkel',
            'email' => 'kabeng@kabeng.com',
            'password' => '12345678',
            'level' => 'kabeng',
        ]);

        User::create([
            'id_users' => '5',
            'name' => 'Siswa',
            'email' => 'siswa@siswa.com',
            'password' => '12345678',
            'level' => 'siswa',
        ]);

        User::create([
            'id_users' => '6',
            'name' => 'Siswa1',
            'email' => 'siswa1@siswa1.com',
            'password' => '12345678',
            'level' => 'siswa',
        ]);

        User::create([
            'id_users' => '7',
            'name' => 'Siswa2',
            'email' => 'siswa2@siswa2.com',
            'password' => '12345678',
            'level' => 'siswa',
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
            'id_barang' => '3',
            'nama_barang' => 'Kabel UTP',
            'merek' => 'Belden',
            'stok_barang' => '10',
            'kode_barang' => null,
            'id_jenis_barang' => '3',
        ]);

        Barang::create([
            'id_barang' => '5',
            'nama_barang' => 'Connector rj45',
            'merek' => 'Belden',
            'stok_barang' => '50',
            'kode_barang' => null,
            'id_jenis_barang' => '3',
        ]);

        Barang::create([
            'id_barang' => '6',
            'nama_barang' => 'Accsess Point',
            'merek' => 'Mikrotik',
            'stok_barang' => null,
            'kode_barang' => 'AP-313892023',
            'id_jenis_barang' => '2',
        ]);

        Inventaris::create([
            'id_inventaris' => '13',
            'id_barang' => '6',
            'id_ruangan' => '1',
            'jumlah_barang' => null,
            'kondisi_barang' => 'lengkap',
            'ket_barang' => ''
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
            'id_barang' => '3',
            'id_ruangan' => '3',
            'jumlah_barang' => '10',
            'kondisi_barang' => 'lengkap',
            'ket_barang' => ''
        ]);

        Guru::create([
            'id_guru' => '1',
            'nip' => '0',
            'nama_guru' => '-',
        ]);

        Karyawan::create([
            'id_karyawan' => '1',
            'nama_karyawan' => '-',
        ]);

        
        \App\Models\Pembelian::factory(5)->create();
        // \App\Models\DetailPembelian::factory(20)->create();
        \App\Models\Inventaris::factory(10)->create();
        // \App\Models\Peminjaman::factory(10)->create();
        // \App\Models\DetailPeminjaman::factory(10)->create();
        \App\Models\Karyawan::factory(10)->create();
        \App\Models\Guru::factory(10)->create();

        EmailConfiguration::create([
            'protocol' => 'smtp', // 'smtp', 'sendmail', 'mail', 'qmail
            'host' => 'smtp.gmail.com',
            'port' => '465',
            'timeout' => '30',
            'username' => 'ghinaathaya13@gmail.com',
            'email' => 'ghinaathaya13@gmail.com',
            'password' => 'kcao bfcy ubon mbfc',
        ]);

        // \App\Models\Pemakaian::factory(10)->create();
        // \App\Models\DetailPemakaian::factory(20)->create();

    }

}