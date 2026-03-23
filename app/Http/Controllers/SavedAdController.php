<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedAdController extends Controller
{
    /**
     * Toggle save/unsave an ad
     */
    public function toggle(Ad $ad)
    {
        $user = Auth::user();
        $saved = $user->toggleSaveAd($ad);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'saved' => $saved,
                'message' => $saved ? 'Annonce sauvegardée !' : 'Annonce retirée des favoris',
                'count' => $user->savedAds()->count()
            ]);
        }
        
        return back()->with('success', $saved ? 'Annonce sauvegardée !' : 'Annonce retirée des favoris');
    }

    /**
     * Get user's saved ads
     */
    public function index()
    {
        $savedAds = Auth::user()->savedAds()
            ->with('user')
            ->orderBy('saved_ads.created_at', 'desc')
            ->paginate(12);
        
        return view('saved-ads.index', compact('savedAds'));
    }

    /**
     * Check if ad is saved (API)
     */
    public function check(Ad $ad)
    {
        return response()->json([
            'saved' => Auth::user()->hasSavedAd($ad)
        ]);
    }
}
