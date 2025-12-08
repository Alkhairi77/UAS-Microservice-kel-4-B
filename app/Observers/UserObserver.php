<?php

namespace App\Observers;

use App\Models\User;
use App\Helpers\NotificationHelper;

class UserObserver
{
    public function created(User $user)
    {
        // Kirim welcome notification setelah user dibuat
        NotificationHelper::welcomeNewUser($user);
    }
}
