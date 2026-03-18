<?php

namespace App\Http\Controllers;

use App\Models\Notify;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
  public function feed(): JsonResponse
  {
    $userId = Auth::id();

    $notifications = Notify::query()
      ->select(['id', 'title', 'description', 'url', 'date', 'read_at'])
      ->where('id_user', $userId)
      ->orderByDesc('date')
      ->limit(8)
      ->get()
      ->map(fn($item) => [
        'id' => $item->id,
        'title' => $item->title ?: 'Notificación',
        'description' => $item->description,
        'date_human' => optional($item->date)->diffForHumans(),
        'is_unread' => is_null($item->read_at),
      ]);

    $unreadCount = Notify::query()
      ->where('id_user', $userId)
      ->whereNull('read_at')
      ->count();

    return response()->json([
      'unreadCount' => $unreadCount,
      'notifications' => $notifications,
    ]);
  }

  public function open(Notify $notification): RedirectResponse
  {
    abort_unless(Auth::check() && Auth::id() === (int) $notification->id_user, 403);

    if (is_null($notification->read_at)) {
      $notification->update(['read_at' => now()]);
    }

    return redirect($notification->url ?: route('home'));
  }

  public function markAllAsRead(): RedirectResponse
  {
    Notify::query()
      ->where('id_user', Auth::id())
      ->whereNull('read_at')
      ->update(['read_at' => now()]);

    return back();
  }

  public function markAllAsReadJson(): JsonResponse
  {
    Notify::query()
      ->where('id_user', Auth::id())
      ->whereNull('read_at')
      ->update(['read_at' => now()]);

    return response()->json(['ok' => true]);
  }
}
