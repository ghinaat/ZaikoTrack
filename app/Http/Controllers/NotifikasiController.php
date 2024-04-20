<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = auth()->user()->notifikasi()->orderBy('created_at', 'desc')->get();
    
        return view('notifikasi.index', [
            'notifikasis' => $notifikasi,
        ]);
    }

    public function detail(Request $request, $id_notifikasi)
    {
        $notifikasi = Notifikasi::find($id_notifikasi);

        if ($notifikasi->id_users != auth()->user()->id_users) {
            return abort(403);
        }

        $notifikasi->is_dibaca = 'dibaca';

        $notifikasi->update();

        return view('notifikasi.detail', [
            'notifikasi' => $notifikasi,
        ]);
    }

    public function fetch(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();
    
        // Ensure the user is authenticated
        if (!$user) {
            return response()->json([
                'error' => 'Unauthenticated user.'
            ], 401);
        }
    
        // Retrieve unread notifications for the user
        // Customize the query according to your application's data model
        $notifications = $user->notifikasi()
            ->where('is_dibaca', '!=', 'dibaca') // Filter unread notifications
            ->latest() // Order by latest notifications first
            ->get();
    
        // Prepare the data to be sent in response
        $data = $notifications->map(function ($notification) {
            return [
                'url' => route('Home', $notification->id_notifikasi), // Replace with your route for the notification
                'title' => $notification->judul, // Replace with your actual field for notification title
                'time' => $notification->created_at->diffForHumans(), // Format time using Laravel helper
            ];
        });
    
        $unreadCount = $user->notifikasi()
        ->where('is_dibaca', '!=', 'dibaca')
        ->count();

        // Return the notifications and count as JSON
        return response()->json([
            'data' => $data,
            'unread_count' => $unreadCount,
        ]);
    }
}
