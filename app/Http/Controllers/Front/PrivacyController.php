<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PrivacyController extends Controller
{
    public function settings(Request $request, string $locale)
    {
        return view('front.privacy.settings', compact('locale'));
    }

    public function exportData(Request $request, string $locale)
    {
        $user = $request->user();

        $data = [
            'account' => [
                'name'       => $user->name,
                'email'      => $user->email,
                'username'   => $user->username,
                'created_at' => $user->created_at->toIso8601String(),
            ],
            'bookmarks' => $user->bookmarks()->with('post.translations')->get()
                ->map(fn($b) => ['post_id' => $b->post_id, 'title' => $b->post?->translation()?->title, 'saved_at' => $b->created_at->toIso8601String()])
                ->toArray(),
            'comments' => $user->comments()->latest()->get()
                ->map(fn($c) => ['body' => $c->body, 'created_at' => $c->created_at->toIso8601String()])
                ->toArray(),
            'reading_history' => $user->readingHistory()->with('post.translations')->latest()->take(200)->get()
                ->map(fn($h) => ['title' => $h->post?->translation()?->title, 'read_at' => $h->created_at->toIso8601String()])
                ->toArray(),
        ];

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, 'my-data-' . now()->format('Y-m-d') . '.json', [
            'Content-Type' => 'application/json',
        ]);
    }

    public function requestDeletion(Request $request, string $locale)
    {
        $request->validate([
            'password'       => 'required|string',
            'confirm_phrase' => 'required|in:DELETE MY ACCOUNT',
        ]);

        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        $user = $request->user();

        // Anonymize rather than hard-delete to preserve referential integrity
        $user->update([
            'name'       => 'Deleted User',
            'email'      => 'deleted_' . $user->id . '@deleted.invalid',
            'username'   => 'deleted_' . $user->id,
            'bio'        => null,
            'is_active'  => false,
        ]);

        // Remove sensitive data
        $user->tokens()->delete();
        $user->notifications()->delete();
        $user->bookmarks()->delete();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home', ['locale' => $locale])
            ->with('success', 'Your account has been deleted.');
    }

    public function updateConsent(Request $request, string $locale)
    {
        $request->validate([
            'analytics'   => 'boolean',
            'marketing'   => 'boolean',
            'preferences' => 'boolean',
        ]);

        session([
            'cookie_consent' => [
                'analytics'   => (bool) $request->analytics,
                'marketing'   => (bool) $request->marketing,
                'preferences' => (bool) $request->preferences,
                'given_at'    => now()->toIso8601String(),
            ],
        ]);

        return response()->json(['message' => 'Consent preferences saved.']);
    }
}
