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
            $user->referral_code = User::generateUniqueReferralCode();
            $user->save();
        }

        $referralLink = url('/register?ref=' . $user->referral_code);

        $referralSummary = $user->getReferralPointsSummary();
        $generationIds = $user->getReferralGenerationIds();

        $generations = [];
        $directReferrals = [];
        foreach ($generationIds as $gen) {
            $level = (int) $gen['level'];
            $ids = $gen['ids'];
            if (empty($ids)) {
                continue;
            }

            $children = User::query()
                ->whereIn('id', $ids)
                ->orderBy('created_at', 'asc')
                ->get(['id', 'name', 'email', 'created_at', 'parent_id']);

            $summaryRow = collect($referralSummary['generations'])->firstWhere('level', $level);

            $row = [
                'level' => $level,
                'count' => $children->count(),
                'multiplier' => $summaryRow['multiplier'] ?? null,
                'points' => $summaryRow['points'] ?? null,
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

            $generations[] = $row;
            if ($level === 1) {
                $directReferrals = $row['users'];
            }
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
            'totalCommunity' => $referralSummary['community_size'],
            'totalPoints' => $referralSummary['total_points'],
        ]);
    }
}
