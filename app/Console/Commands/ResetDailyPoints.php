<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetDailyPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'points:daily-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réinitialise les points journaliers des utilisateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = User::where('daily_points', '>', 0)
                     ->orWhere('last_daily_reset', '<', now()->subDay())
                     ->update([
                         'daily_points' => 0,
                         'last_daily_reset' => now()
                     ]);

        $this->info("Points journaliers réinitialisés pour $count utilisateur(s).");
        
        // Log pour le suivi
        Log::info("Points journaliers réinitialisés", ['count' => $count]);
        
        return Command::SUCCESS;
    }
}
