<?php

namespace App\Services;

use App\Models\Notify;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
  public function notifyUser(int $userId, string $title, ?string $description = null, ?string $url = null): void
  {
    Notify::create([
      'id_user' => $userId,
      'title' => $title,
      'description' => $description,
      'url' => $url,
      'date' => now(),
      'read_at' => null,
    ]);
  }

  public function notifyUsers(Collection $userIds, string $title, ?string $description = null, ?string $url = null): void
  {
    $rows = $userIds
      ->unique()
      ->filter()
      ->map(fn($userId) => [
        'id_user' => (int) $userId,
        'title' => $title,
        'description' => $description,
        'url' => $url,
        'date' => now(),
        'read_at' => null,
      ])
      ->values()
      ->all();

    if (!empty($rows)) {
      Notify::insert($rows);
    }
  }

  public function notifyByRolePermissions(array $permissions, string $title, ?string $description = null, ?string $url = null): void
  {
    $roleIds = Roles::query()
      ->whereIn('permissions', $permissions)
      ->pluck('id');

    if ($roleIds->isEmpty()) {
      return;
    }

    $userIds = User::query()
      ->whereIn('id_rol', $roleIds)
      ->pluck('id');

    $this->notifyUsers($userIds, $title, $description, $url);
  }
}
