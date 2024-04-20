<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\NotificationResource;
use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $notifications = $user->notifications()
            ->where('type', 'unapproved-manga')
            ->get();

        return $this->success([
            'data' => NotificationResource::collection($notifications),
        ], 200, true);
    }

    public function getUnreadNotifications()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $notifications = $user->unreadNotifications()
            ->where('type', '!=', 'unapproved-manga')
            ->get();

        return $this->success([
            'data' => NotificationResource::collection($notifications),
        ], 200, true);
    }

    public function delete(string $id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $notification = $user->unreadNotifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            $this->failedAsNotFound('Notification');
        }

        $status = $notification->delete();

        if (!$status) {
            $this->failure([
                'message' => 'Could not delete the notification',
            ]);
        }

        $this->success([
            'message' => 'Notification is deleted',
        ]);
    }

    public function markAsRead(string $id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $notification = $user->unreadNotifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            $this->failedAsNotFound('Notification');
        }

        $notification->markAsRead();

        $this->success([
            'message' => 'Notification is marked as read',
        ]);
    }
}
