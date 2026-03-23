<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $reviewedUser = User::findOrFail($id);

        if (Auth::id() === $reviewedUser->id) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'ad_id' => 'nullable|integer|exists:ads,id',
        ]);

        Review::updateOrCreate(
            [
                'reviewer_id' => Auth::id(),
                'reviewed_user_id' => $reviewedUser->id,
                'ad_id' => $validated['ad_id'] ?? null,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return redirect()->route('profile.public', $reviewedUser->id)
            ->with('success', 'Merci pour votre avis !');
    }
}
