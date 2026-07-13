<?php

namespace App\Http\Controllers;

use App\Core\Engines\NotificationEngine;
use App\Core\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = app(NotificationEngine::class)->allFor(auth()->id())->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function read(Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->markAsRead();

        return redirect()->back();
    }
}
