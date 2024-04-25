<?php

namespace App\Listeners;

use App\Events\NotifPeminjaman;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Queue\InteractsWithQueue;

class NotifikasiPeminjaman
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    
    public function handle(NotifPeminjaman $event)
    {       
        $detailPeminjaman = $event->detail_peminjaman;

        // dd($detailPeminjaman);

        foreach ($detailPeminjaman as $detailPeminjaman) {
            // Memeriksa status detailPeminjaman
            if ($detailPeminjaman->status == 'dipinjam') {
                // Mendapatkan tanggal kembali dari detailPeminjaman yang terkait
                $tgl_kembali = $detailPeminjaman->tgl_kembali;
                // dd($tgl_kembali);
                $tgl_kembali = Carbon::parse($detailPeminjaman->tgl_kembali);
                $selisih_waktu = Carbon::now()->diff($tgl_kembali);
                $true_selisih =$selisih_waktu->invert == 1;
            //  dd($true_selisih);
                // Check apakah tanggal kembali telah lewat
                if ($true_selisih) {
                    // Peminjaman sudah lewat tanggal kembali, lakukan notifikasi
                    if ($detailPeminjaman->id_users) {
                    $pengguna = User::findOrFail($detailPeminjaman->id_users);
                    // Buat notifikasi untuk pengguna
                    $notifikasi = new Notifikasi();
                    $notifikasi->judul = 'Peminjaman Barang';
                    $notifikasi->pesan = 'Peminjaman barang yang Anda lakukan belum dikembalikan. Mohon segera mengembalikan barang yang dipinjam untuk mencegah keterlambatan.';
                    $notifikasi->is_dibaca = 'tidak_dibaca';
                    $notifikasi->label = 'info';
                    $notifikasi->send_email = 'yes';
                    $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
                    $notifikasi->id_users = $pengguna->id_users;
                    $notifikasi->save();
                
                    
                    // dd($notifikasi);
        
                    // Buat notifikasi untuk teknisi
                    $notifikasiTeknisi = User::where('level', 'teknisi')->get();
                    foreach ($notifikasiTeknisi as $na) {
                        $notifikasi = new Notifikasi();
                        $notifikasi->judul = 'Peminjaman Barang';
                        $notifikasi->pesan = 'Peminjaman dari '.$pengguna->name.' sudah melewati batas waktu peminjaman.'; 
                        $notifikasi->is_dibaca = 'tidak_dibaca';
                        $notifikasi->label = 'info';
                        $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
                        $notifikasi->send_email = 'no';
                        $notifikasi->id_users = $na->id_users;
                        $notifikasi->save();


                    }
                }else{
                    $notifikasiTeknisi = User::where('level', 'teknisi')->get();
                    foreach ($notifikasiTeknisi as $na) {
                        $notifikasi = new Notifikasi();
                        $notifikasi->judul = 'Peminjaman Barang';
                        $notifikasi->pesan = 'Terdapat peminjaman yang sudah melewati batas waktu peminjaman. Dimohon untuk segera memeriksa data peminjam.'; 
                        $notifikasi->is_dibaca = 'tidak_dibaca';
                        $notifikasi->label = 'info';
                        $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
                        $notifikasi->send_email = 'no';
                        $notifikasi->id_users = $na->id_users;
                        $notifikasi->save();


                    }
                }
                }
            }
        }
    }
}