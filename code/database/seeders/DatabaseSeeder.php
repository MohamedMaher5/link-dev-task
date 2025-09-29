<?php

namespace Database\Seeders;

use App\Models\AvailabilityOverride;
use App\Models\ProviderAvailability;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::factory()->admin()->create([
            'name' => 'admin',
            'email' => 'admin@linkdev.com'
        ]);

        foreach (range(1, 5) as $i) {
            $provider = User::factory()->provider()->create([
                'name' => "provider{$i}",
                'email' => "provider{$i}@linkdev.com",
            ]);

            Service::factory()->count(5)->for($provider, 'provider')->create();
            ProviderAvailability::factory()->count(5)->for($provider, 'provider')->create();
            AvailabilityOverride::factory()->count(2)->for($provider, 'provider')->create();


            User::factory()->customer()->create([
                'name' => "customer{$i}",
                'email' => "customer{$i}@linkdev.com",
            ]);
        }


//        User::factory()->count(5)->provider()->create()
//            ->each(function ($provider) {
//                Service::factory()->count(5)->for($provider, 'provider')->create();
//                ProviderAvailability::factory()->count(5)->for($provider, 'provider')->create();
//                AvailabilityOverride::factory()->count(2)->for($provider, 'provider')->create();
//            });

//        User::factory()->count(5)->customer()->create();
    }
}
