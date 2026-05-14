<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user()->load('roles', 'subscriptions.plan');

        return response()->json([
            'id'                  => $user->id,
            'name'                => $user->name,
            'email'               => $user->email,
            'username'            => $user->username,
            'bio'                 => $user->bio ?? null,
            'avatar'              => $user->profile_photo_url,
            'roles'               => $user->roles->pluck('name'),
            'is_verified'         => (bool) ($user->is_verified_journalist ?? false),
            'active_plan'         => $user->subscriptions->where('status', 'active')->first()?->plan?->name,
            'unread_notifications' => $user->unreadNotifications()->count(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'username' => ['sometimes', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'bio'      => 'sometimes|nullable|string|max:1000',
        ]);

        $user->update($data);

        return response()->json(['message' => 'Profile updated.']);
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($data['current_password'], $request->user()->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $request->user()->update(['password' => Hash::make($data['password'])]);
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Password changed. Please log in again.']);
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(20);
        return NotificationResource::collection($notifications);
    }

    public function markNotificationsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
