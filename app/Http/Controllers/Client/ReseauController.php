<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReseauController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->referral_code) {
            $user->referral_code = User::generateReferralCodeIfExists();
            $user->save();
        }

        $referralLink = url('/register?ref=' . $user->referral_code);

        $generations = [];
        $currentIds = [$user->id];
        $level = 1;
        $totalCommunity = 0;

        while (true) {
            $children = User::query()
                ->whereIn('parent_id', $currentIds)
                ->orderBy('created_at', 'asc')
                ->get(['id', 'name', 'email', 'created_at', 'parent_id']);

            if ($children->isEmpty()) {
                break;
            }

            $generations[] = [
                'level' => $level,
                'count' => $children->count(),
                'users' => $children->map(function (User $u) {
                    return [
                        'id' => $u->id,
                        'name' => $u->name,
                        'email' => $u->email,
                        'created_at' => optional($u->created_at)->toIso8601String(),
                        'parent_id' => $u->parent_id,
                    ];
                })->values(),
            ];

            $totalCommunity += $children->count();
            $currentIds = $children->pluck('id')->all();
            $level++;

            if ($level > 50) {
                break;
            }
        }

        $directReferrals = [];
        if (!empty($generations)) {
            $directReferrals = $generations[0]['users'];
        }

        return inertia('Client/Reseau', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'referralLink' => $referralLink,
            'directReferrals' => $directReferrals,
            'generations' => $generations,
            'totalCommunity' => $totalCommunity,
        ]);
    }
}
