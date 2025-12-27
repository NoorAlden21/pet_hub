<?php

namespace App\Services;

use App\Models\User;

class UserNotificationsService
{
    public function notifyUser(User $user, string $type, string $title, string $body, array $data = []): void
    {
        $user->notifications()->create([
            'type'  => $type,
            'title' => $title,
            'body'  => $body,
            'data'  => $data,
        ]);
    }

    public function notifyAdmins(string $type, string $title, string $body, array $data = []): void
    {
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $this->notifyUser($admin, $type, $title, $body, $data);
        }
    }

    public function notifyUsers(array $users, string $type, string $title, string $body, array $data = []): void
    {
        foreach ($users as $user) {
            $this->notifyUser($user, $type, $title, $body, $data);
        }
    }
}
