<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;

/**
 * @deprecated Les abonnements ont été supprimés.
 * Ce contrôleur est conservé pour la compatibilité mais ne devrait plus être utilisé.
 * Utilisez StripeCheckoutController et PricingController à la place.
 */
class SubscriptionController extends Controller
{
    /**
     * Plans d'abonnement disponibles
     */
    private $plans = [
        'basic' => [
            'name' => 'Basic',
            'price' => 9.99,
            'price_id' => 'price_basic_monthly', // À remplacer par l'ID Stripe
            'features' => [
                '10 annonces par mois',
                'Mise en avant basique',
                'Support par email',
                '50 points bonus/mois',
            ],
            'ads_limit' => 10,
            'bonus_points' => 50,
        ],
        'pro' => [
            'name' => 'Pro',
            'price' => 24.99,
            'price_id' => 'price_pro_monthly', // À remplacer par l'ID Stripe
            'features' => [
                '50 annonces par mois',
                'Mise en avant premium',
                'Support prioritaire',
                '150 points bonus/mois',
                'Badge Pro',
                'Statistiques avancées',
            ],
            'ads_limit' => 50,
            'bonus_points' => 150,
        ],
        'business' => [
            'name' => 'Business',
            'price' => 49.99,
            'price_id' => 'price_business_monthly', // À remplacer par l'ID Stripe
            'features' => [
                'Annonces illimitées',
                'Mise en avant maximale',
                'Support VIP 24/7',
                '500 points bonus/mois',
                'Badge Business',
                'API accès',
                'Compte multi-utilisateurs',
            ],
            'ads_limit' => -1, // Illimité
            'bonus_points' => 500,
        ],
    ];

    /**
     * Afficher les plans d'abonnement
     */
    public function index()
    {
        $user = Auth::user();
        $currentPlan = null;
        $subscription = null;

        if ($user->subscribed('default')) {
            $subscription = $user->subscription('default');
            foreach ($this->plans as $key => $plan) {
                if ($subscription->stripe_price === $plan['price_id']) {
                    $currentPlan = $key;
                    break;
                }
            }
        }

        return view('subscriptions.index', [
            'plans' => $this->plans,
            'currentPlan' => $currentPlan,
            'subscription' => $subscription,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * S'abonner à un plan
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,pro,business',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $plan = $this->plans[$request->plan];

        try {
            // Si déjà abonné, changer de plan
            if ($user->subscribed('default')) {
                $user->subscription('default')
                    ->swap($plan['price_id']);
                
                $message = 'Votre abonnement a été mis à niveau vers ' . $plan['name'] . ' !';
            } else {
                // Nouvel abonnement
                $user->newSubscription('default', $plan['price_id'])
                    ->create($request->payment_method);
                
                // Ajouter les points bonus via le nouveau système
                $user->addPoints($plan['bonus_points'], 'subscription_bonus', 'Bonus d\'abonnement ' . $plan['name']);

                $message = 'Bienvenue dans le plan ' . $plan['name'] . ' ! ' . $plan['bonus_points'] . ' points bonus ont été ajoutés à votre compte.';
            }

            return redirect()->route('subscriptions.index')
                ->with('success', $message);

        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('subscriptions.index')]
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Annuler l'abonnement
     */
    public function cancel()
    {
        $user = Auth::user();

        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();
            
            return redirect()->route('subscriptions.index')
                ->with('success', 'Votre abonnement a été annulé. Vous conservez l\'accès jusqu\'à la fin de la période payée.');
        }

        return redirect()->route('subscriptions.index')
            ->with('error', 'Aucun abonnement actif trouvé.');
    }

    /**
     * Reprendre un abonnement annulé
     */
    public function resume()
    {
        $user = Auth::user();

        if ($user->subscription('default')->onGracePeriod()) {
            $user->subscription('default')->resume();
            
            return redirect()->route('subscriptions.index')
                ->with('success', 'Votre abonnement a été réactivé !');
        }

        return redirect()->route('subscriptions.index')
            ->with('error', 'Impossible de reprendre cet abonnement.');
    }

    /**
     * Succès après paiement
     */
    public function success()
    {
        return redirect()->route('subscriptions.index')
            ->with('success', 'Paiement effectué avec succès !');
    }

    /**
     * Télécharger les factures
     */
    public function invoices()
    {
        $user = Auth::user();
        
        return view('subscriptions.invoices', [
            'invoices' => $user->invoices(),
        ]);
    }

    /**
     * Télécharger une facture spécifique
     */
    public function downloadInvoice($invoiceId)
    {
        return Auth::user()->downloadInvoice($invoiceId, [
            'vendor' => 'ProxiPro',
            'product' => 'Abonnement Premium',
        ]);
    }
}
