<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointTransaction;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PricingController extends Controller
{
    /**
     * Packs de points disponibles (alignés sur les tarifs boost)
     */
    private $pointPacks = [
        'pack_5' => [
            'name' => 'Pack Boost 3j',
            'points' => 5,
            'price' => 4.00,
            'bonus' => 0,
            'description' => 'Idéal pour booster une annonce 3 jours',
        ],
        'pack_10' => [
            'name' => 'Pack Boost 7j',
            'points' => 10,
            'price' => 6.00,
            'bonus' => 0,
            'description' => 'Idéal pour booster une annonce 7 jours',
            'popular' => true,
        ],
        'pack_20' => [
            'name' => 'Pack Boost 15j',
            'points' => 20,
            'price' => 10.00,
            'bonus' => 0,
            'description' => 'Idéal pour booster une annonce 15 jours',
        ],
        'pack_30' => [
            'name' => 'Pack Boost 30j',
            'points' => 30,
            'price' => 15.00,
            'bonus' => 0,
            'description' => 'Idéal pour booster une annonce 30 jours',
        ],
        'pack_50' => [
            'name' => 'Pack Confort',
            'points' => 50,
            'price' => 22.00,
            'bonus' => 0,
            'description' => 'Pour booster et vérifier votre profil',
        ],
        'pack_100' => [
            'name' => 'Pack Pro',
            'points' => 100,
            'price' => 40.00,
            'bonus' => 0,
            'description' => 'Pour une utilisation intensive',
            'best_value' => true,
        ],
    ];

    /**
     * Afficher la page de tarification unifiée
     */
    public function index()
    {
        $user = Auth::user();
        $userPoints = $user->available_points ?? 0;

        return view('pricing.checkout', [
            'pointPacks' => $this->pointPacks,
            'userPoints' => $userPoints,
        ]);
    }

    /**
     * @deprecated Les abonnements ont été supprimés.
     */
    public function subscribe(Request $request)
    {
        return redirect()->route('pricing.index')
            ->with('info', 'Les abonnements ne sont plus disponibles. Utilisez les packs de points à la place.');
    }

    /**
     * @deprecated Les abonnements ont été supprimés.
     */
    public function cancel()
    {
        return redirect()->route('pricing.index')
            ->with('info', 'Les abonnements ne sont plus disponibles.');
    }

    /**
     * @deprecated Les abonnements ont été supprimés.
     */
    public function resume()
    {
        return redirect()->route('pricing.index')
            ->with('info', 'Les abonnements ne sont plus disponibles.');
    }

    /**
     * Acheter des points
     */
    public function purchasePoints(Request $request)
    {
        $request->validate([
            'pack' => 'required|in:small,medium,large,mega',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $pack = $this->pointPacks[$request->pack];
        $totalPoints = $pack['points'] + $pack['bonus'];

        try {
            // Créer un paiement unique
            $payment = $user->charge(
                $pack['price'] * 100, // En centimes
                $request->payment_method,
                [
                    'description' => 'ProxiPro - ' . $pack['name'] . ' (' . $totalPoints . ' points)',
                ]
            );

            if ($payment->status === 'succeeded') {
                // Ajouter les points via le nouveau système
                $user->addPoints($totalPoints, 'purchase', 'Achat ' . $pack['name'] . ' - ' . $pack['points'] . ' points' . 
                    ($pack['bonus'] > 0 ? ' + ' . $pack['bonus'] . ' bonus' : ''), 'stripe');

                return redirect()->route('pricing.index')
                    ->with('success', 'Félicitations ! ' . number_format($totalPoints, 0, ',', ' ') . ' points ont été ajoutés à votre compte !');
            }

            return back()->with('error', 'Le paiement n\'a pas pu être complété.');

        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('pricing.index')]
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du paiement: ' . $e->getMessage());
        }
    }
}
