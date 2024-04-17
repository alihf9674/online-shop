<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function readAll()
    {
        $notifications = Notification::all();
        foreach ($notifications as $notification) {
            $notification->update(['read_at' => now()]);
        }
    }
}
