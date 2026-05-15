<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar notifikasi untuk user yang login
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = Notifikasi::getRoleName($user->id_hak_akses);

        $notifikasi = Notifikasi::where(function($query) use ($user, $roleName) {
            $query->where('id_user_penerima', $user->id_user)
                  ->orWhere('role_penerima', $roleName);
        })
        ->orderBy('tgl_kirim', 'desc')
        ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $roleName = Notifikasi::getRoleName($user->id_hak_akses);

        $notifikasi = Notifikasi::where(function($query) use ($user, $roleName) {
            $query->where('id_user_penerima', $user->id_user)
                  ->orWhere('role_penerima', $roleName);
        })->where('id_notifikasi', $id)->firstOrFail();

        $notifikasi->update(['is_read' => true]);

        if ($notifikasi->link) {
            return redirect($notifikasi->link);
        }

        return redirect()->back();
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllRead()
    {
        $user = Auth::user();
        $roleName = Notifikasi::getRoleName($user->id_hak_akses);

        Notifikasi::where(function($query) use ($user, $roleName) {
            $query->where('id_user_penerima', $user->id_user)
                  ->orWhere('role_penerima', $roleName);
        })->where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * Get unread count (API)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $roleName = Notifikasi::getRoleName($user->id_hak_akses);

        $count = Notifikasi::where(function($query) use ($user, $roleName) {
            $query->where('id_user_penerima', $user->id_user)
                  ->orWhere('role_penerima', $roleName);
        })->where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }
}
