<?php

namespace App\Livewire\Site;

use App\Models\Notify;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HeaderNotificationsController extends Component
{
  public $notifications = [];
  public int $unreadCount = 0;

  public function mount(): void
  {
    $this->loadNotifications();
  }

  public function loadNotifications(): void
  {
    if (!Auth::check()) {
      $this->notifications = [];
      $this->unreadCount = 0;
      return;
    }

    $userId = Auth::id();

    $this->notifications = Notify::query()
      ->select(['id', 'title', 'description', 'url', 'date', 'read_at'])
      ->where('id_user', $userId)
      ->orderByDesc('date')
      ->limit(8)
      ->get();

    $this->unreadCount = Notify::query()
      ->where('id_user', $userId)
      ->whereNull('read_at')
      ->count();
  }

  public function markAllAsRead(): void
  {
    if (!Auth::check()) {
      return;
    }

    Notify::query()
      ->where('id_user', Auth::id())
      ->whereNull('read_at')
      ->update(['read_at' => now()]);

    $this->loadNotifications();
  }

  public function openNotification(int $notificationId)
  {
    if (!Auth::check()) {
      return;
    }

    $notification = Notify::query()
      ->where('id', $notificationId)
      ->where('id_user', Auth::id())
      ->first();

    if (!$notification) {
      return;
    }

    if (is_null($notification->read_at)) {
      $notification->update(['read_at' => now()]);
    }

    $this->loadNotifications();

    return redirect()->to($notification->url ?: route('home'));
  }

  public function render()
  {
    return view('livewire.site.header-notifications');
  }
}
