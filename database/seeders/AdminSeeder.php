<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.principal_admin.email');
        $name = config('admin.principal_admin.name', 'Admin');

        // Ne crée le compte que s'il n'existe pas déjà
        if (!User::where('email', $email)->exists()) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(env('ADMIN_PASSWORD', 'ProxiPro2026!')),
                'role' => 'admin',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info("Compte admin créé : {$email}");
        } else {
            // S'assurer que le compte existant a bien le rôle admin
            User::where('email', $email)->update(['role' => 'admin']);
            $this->command->info("Compte admin déjà existant : {$email}");
        }
    }
}
