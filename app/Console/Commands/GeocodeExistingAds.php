<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ad;
use App\Services\GeocodingService;

class GeocodeExistingAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:geocode {--force : Forcer le géocodage même pour les annonces déjà géolocalisées}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Géocode les annonces existantes qui n\'ont pas encore de coordonnées GPS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $geocodingService = new GeocodingService();
        
        // Récupérer les annonces sans coordonnées (ou toutes si --force)
        $query = Ad::whereNotNull('location');
        
        if (!$this->option('force')) {
            $query->whereNull('latitude');
        }
        
        $ads = $query->get();
        
        if ($ads->isEmpty()) {
            $this->info('Aucune annonce à géocoder.');
            return Command::SUCCESS;
        }
        
        $this->info("Géocodage de {$ads->count()} annonce(s)...");
        $bar = $this->output->createProgressBar($ads->count());
        $bar->start();
        
        $success = 0;
        $failed = 0;
        
        foreach ($ads as $ad) {
            $result = $geocodingService->geocode($ad->location);
            
            if ($result['latitude'] && $result['longitude']) {
                $ad->update([
                    'latitude' => $result['latitude'],
                    'longitude' => $result['longitude'],
                    'address' => $result['address'] ?? $ad->location,
                    'postal_code' => $result['postal_code'] ?? null,
                    'country' => $result['country'] ?? 'France',
                ]);
                $success++;
            } else {
                $failed++;
                $this->line("\n  <fg=yellow>⚠</> Échec pour: {$ad->location}");
            }
            
            $bar->advance();
            
            // Pause pour respecter les limites API Nominatim (1 req/seconde)
            sleep(1);
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("✅ Géocodage terminé !");
        $this->table(
            ['Statut', 'Nombre'],
            [
                ['Succès', $success],
                ['Échecs', $failed],
            ]
        );
        
        return Command::SUCCESS;
    }
}
