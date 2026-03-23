<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/feed';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('throttle:5,1'); // Max 5 registration attempts per minute
    }

    /**
     * Handle a registration request for the application.
     * Overridden to add honeypot & timing checks.
     */
    public function register(Request $request)
    {
        // ── Honeypot check: if the hidden field is filled, it's a bot ──
        if ($request->filled('website_url')) {
            Log::warning('Bot registration blocked (honeypot)', [
                'ip' => $request->ip(),
                'email' => $request->input('email'),
                'user_agent' => $request->userAgent(),
            ]);
            // Return a fake success to confuse bots
            return redirect($this->redirectPath())
                ->with('status', 'Inscription réussie !');
        }

        // ── Timing check: form filled too fast = bot ──
        $formRenderedAt = $request->input('_form_token');
        if ($formRenderedAt) {
            try {
                $renderedTime = (int) decrypt($formRenderedAt);
                $elapsed = time() - $renderedTime;
                if ($elapsed < 3) { // Less than 3 seconds = bot
                    Log::warning('Bot registration blocked (timing)', [
                        'ip' => $request->ip(),
                        'elapsed_seconds' => $elapsed,
                        'email' => $request->input('email'),
                    ]);
                    return redirect($this->redirectPath())
                        ->with('status', 'Inscription réussie !');
                }
            } catch (\Exception $e) {
                // Invalid token - might be tampered
                Log::warning('Invalid form token during registration', [
                    'ip' => $request->ip(),
                ]);
            }
        }

        // Proceed with normal registration (from RegistersUsers trait)
        $this->validator($request->all())->validate();
        
        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            Log::warning('Welcome email failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'password' => [
                'required', 
                'string', 
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ],
            'terms' => ['required', 'accepted'],
            // Honeypot: must be empty
            'website_url' => ['max:0'],
        ];

        // reCAPTCHA v3 (only if configured)
        if (config('services.recaptcha.secret_key')) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha('register')];
        }

        // Validation selon le type de compte
        if (isset($data['account_type']) && $data['account_type'] === 'professionnel') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['business_type'] = ['required', 'in:entreprise,auto_entrepreneur'];
            $rules['siret'] = ['nullable', 'string', 'size:14'];
            $rules['sector'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['firstname'] = ['required', 'string', 'max:255'];
            $rules['lastname'] = ['required', 'string', 'max:255'];
        }

        return Validator::make($data, $rules, [
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required' => 'Le nom est obligatoire.',
            'company_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'business_type.required' => 'Veuillez choisir entre Entreprise ou Auto-entrepreneur.',
            'business_type.in' => 'Type d\'activité invalide.',
            'siret.size' => 'Le SIRET doit contenir exactement 14 chiffres.',

            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.mixed' => 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            'g-recaptcha-response.required' => 'La vérification de sécurité est requise. Veuillez réessayer.',
            'website_url.max' => '',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $isProfessionnel = isset($data['account_type']) && $data['account_type'] === 'professionnel';
        
        // Construire le nom selon le type de compte
        if ($isProfessionnel) {
            $name = $data['company_name'];
            $accountType = 'professionnel';
            $userType = 'professionnel';
            $businessType = $data['business_type'] ?? 'auto_entrepreneur';
            
            // Définir les limites selon le type de business
            $maxActiveAds = $businessType === 'entreprise' ? 20 : 10;
        } else {
            $name = trim($data['firstname'] . ' ' . $data['lastname']);
            $accountType = 'particulier';
            $userType = 'particulier';
            $businessType = null;
            $maxActiveAds = 5;
        }

        // Supprimer définitivement tout utilisateur soft-deleted avec le même email
        // pour libérer la contrainte UNIQUE avant la création
        $trashedUser = User::withTrashed()
            ->where('email', $data['email'])
            ->whereNotNull('deleted_at')
            ->first();
        
        if ($trashedUser) {
            Log::info('Force-deleting soft-deleted user to allow re-registration', [
                'old_user_id' => $trashedUser->id,
                'email' => $data['email'],
            ]);
            $trashedUser->forceDelete();
        }

        $user = User::create([
            'name' => $name,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'user_type' => $userType,
            'account_type' => $accountType,
            'business_type' => $businessType,
            'company_name' => $isProfessionnel ? $data['company_name'] : null,
            'siret' => $isProfessionnel ? ($data['siret'] ?? null) : null,
            'business_sector' => $isProfessionnel ? ($data['sector'] ?? null) : null,
            'service_category' => null,
            'service_subcategories' => null,
            'profession' => null,
            'newsletter_subscribed' => isset($data['newsletter']),
            'is_service_provider' => $isProfessionnel,
            'service_provider_since' => $isProfessionnel ? now() : null,
        ]);

        // Set protected fields explicitly (not mass-assignable for security)
        $user->role = 'user';
        $user->max_active_ads = $maxActiveAds;
        $user->is_active = true;
        $user->save();

        // Ajouter des points de bienvenue (5 points pour tous)
        if (class_exists(\App\Models\PointTransaction::class)) {
            try {
                $welcomePoints = 5; // 5 points gratuits à l'inscription
                
                \App\Models\PointTransaction::create([
                    'user_id' => $user->id,
                    'points' => $welcomePoints,
                    'type' => 'welcome_bonus',
                    'description' => 'Bonus de bienvenue à l\'inscription (5 points gratuits)',
                ]);
                
                // Créditer available_points et total_points (colonnes réelles)
                $user->increment('available_points', $welcomePoints);
                $user->increment('total_points', $welcomePoints);
            } catch (\Exception $e) {
                // Log error but don't fail registration
                \Log::error('Failed to add welcome points: ' . $e->getMessage());
            }
        }

        return $user;
    }
}
