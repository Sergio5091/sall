<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'telephone' => $request->user()->telephone,
                    'bio' => $request->user()->bio,
                    'company' => $request->user()->company,
                    'experience' => $request->user()->experience,
                    'favorite_games' => $request->user()->favorite_games,
                    'specialties' => $request->user()->specialties,
                    'social_links' => $request->user()->social_links,
                    'profile_photo_url' => $request->user()->profile_photo_url,
                    'preferences' => $request->user()->preferences,
                    'password_updated_at' => $request->user()->password_updated_at,
                    'created_at' => $request->user()->created_at,
                    'email_verified_at' => $request->user()->email_verified_at,
                    'role' => $request->user()->role,
                    'status' => $request->user()->status,
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'info' => fn () => $request->session()->get('info'),
            ],
            'errors' => fn () => $request->session()->get('errors')
                ? $request->session()->get('errors')->getBag('default')->getMessages()
                : (object) [],
        ]);
    }
}
