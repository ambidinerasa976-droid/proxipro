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
            $admin = new User();
            $admin->name = $name;
            $admin->email = $email;
            $admin->password = Hash::make(env('ADMIN_PASSWORD', 'ProxiPro2026!'));
            $admin->role = 'admin';
            $admin->is_verified = true;
            $admin->is_active = true;
            $admin->email_verified_at = now();
            $admin->user_type = 'particulier';
            $admin->save();

            $this->command->info("Compte admin créé : {$email}");
        } else {
            // S'assurer que le compte existant a bien le rôle admin
            User::where('email', $email)->update(['role' => 'admin']);
            $this->command->info("Compte admin déjà existant : {$email}");
        }
    }
}
