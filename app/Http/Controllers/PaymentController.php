<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointTransaction;

/**
 * @deprecated Remplacé par StripeCheckoutController.
 * Ce contrôleur est conservé pour la compatibilité mais ne devrait plus être utilisé.
 * Voir PricingController + StripeCheckoutController pour le nouveau système.
 */
class PaymentController extends Controller
{
    /**
     * Packs de points disponibles à l'achat
     */
    private $pointPacks = [
        'small' => [
            'name' => 'Pack Starter',
            'points' => 100,
            'price' => 4.99,
            'bonus' => 0,
            'price_id' => 'price_points_100', // À remplacer par l'ID Stripe
        ],
        'medium' => [
            'name' => 'Pack Standard',
            'points' => 500,
            'price' => 19.99,
            'bonus' => 50,
            'price_id' => 'price_points_500',
            'popular' => true,
        ],
        'large' => [
            'name' => 'Pack Premium',
            'points' => 1000,
            'price' => 34.99,
            'bonus' => 150,
            'price_id' => 'price_points_1000',
        ],
        'mega' => [
            'name' => 'Pack Mega',
            'points' => 5000,
            'price' => 149.99,
            'bonus' => 1000,
            'price_id' => 'price_points_5000',
            'best_value' => true,
        ],
    ];

    /**
     * Afficher la page d'achat de points
     */
    public function buyPoints()
    {
        $user = Auth::user();

        return view('payments.points', [
            'pointPacks' => $this->pointPacks,
            'userPoints' => $user->available_points ?? 0,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * Traiter l'achat de points
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

                return redirect()->route('buy-points')
                    ->with('success', 'Félicitations ! ' . $totalPoints . ' points ont été ajoutés à votre compte !');
            }

            return back()->with('error', 'Le paiement n\'a pas pu être complété.');

        } catch (\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('buy-points')]
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Succès après achat de points
     */
    public function pointsSuccess(Request $request)
    {
        return redirect()->route('buy-points')
            ->with('success', 'Paiement effectué avec succès !');
    }

    /**
     * Annulation de l'achat
     */
    public function pointsCancel()
    {
        return redirect()->route('buy-points')
            ->with('info', 'L\'achat a été annulé.');
    }

    /**
     * Webhook Stripe pour gérer les événements
     */
    public function stripeWebhook(Request $request)
    {
        // Ce webhook est géré automatiquement par Laravel Cashier
        // Vous pouvez ajouter des logiques personnalisées ici

        $payload = $request->all();
        $event = $payload['type'] ?? null;

        switch ($event) {
            case 'invoice.payment_succeeded':
                // Logique pour paiement réussi
                \Log::info('Payment succeeded', $payload);
                break;

            case 'customer.subscription.deleted':
                // Logique pour abonnement annulé
                \Log::info('Subscription cancelled', $payload);
                break;

            case 'invoice.payment_failed':
                // Logique pour échec de paiement
                \Log::warning('Payment failed', $payload);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Historique des transactions de points
     */
    public function pointsHistory()
    {
        $user = Auth::user();
        
        $transactions = PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payments.history', [
            'transactions' => $transactions,
            'userPoints' => $user->available_points ?? 0,
        ]);
    }
}
