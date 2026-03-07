<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
  public function index(Request $request)
  {
    $notifications = $request->user()
      ->notifications()
      ->latest()
      ->paginate(20);

    return view('notifications.index', compact('notifications'));
  }

  public function markAllRead(Request $request)
  {
    $request->user()->unreadNotifications->markAsRead();

    return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
  }
}
