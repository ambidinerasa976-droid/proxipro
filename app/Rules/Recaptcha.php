<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Validation rule for Google reCAPTCHA v3.
 * Verifies the token with Google's API and checks the score.
 */
class Recaptcha implements ValidationRule
{
    protected string $action;

    public function __construct(string $action = 'register')
    {
        $this->action = $action;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');
        $minScore = config('services.recaptcha.min_score', 0.5);

        // If no secret key is configured, skip validation (dev mode)
        if (empty($secretKey)) {
            Log::warning('reCAPTCHA secret key not configured - skipping validation');
            return;
        }

        if (empty($value)) {
            $fail('La vérification de sécurité a échoué. Veuillez réessayer.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            if (!($result['success'] ?? false)) {
                Log::warning('reCAPTCHA verification failed', [
                    'errors' => $result['error-codes'] ?? [],
                    'ip' => request()->ip(),
                ]);
                $fail('La vérification de sécurité a échoué. Veuillez réessayer.');
                return;
            }

            // Check action matches
            if (($result['action'] ?? '') !== $this->action) {
                Log::warning('reCAPTCHA action mismatch', [
                    'expected' => $this->action,
                    'got' => $result['action'] ?? 'unknown',
                    'ip' => request()->ip(),
                ]);
                $fail('La vérification de sécurité a échoué. Veuillez réessayer.');
                return;
            }

            // Check score (0.0 = bot, 1.0 = human)
            $score = $result['score'] ?? 0;
            if ($score < $minScore) {
                Log::warning('reCAPTCHA low score - possible bot', [
                    'score' => $score,
                    'min_score' => $minScore,
                    'ip' => request()->ip(),
                    'action' => $this->action,
                ]);
                $fail('Activité suspecte détectée. Si vous êtes un humain, veuillez réessayer.');
                return;
            }

            Log::info('reCAPTCHA passed', ['score' => $score, 'action' => $this->action]);

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            // Don't block registration if reCAPTCHA service is down
            // but log it for monitoring
        }
    }
}
