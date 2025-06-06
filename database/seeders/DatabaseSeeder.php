<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call
        ([
            SpecialtySeeder::class,
            CitySeeder::class,
        ]);
        // Buat role
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'photographer']);
        Role::create(['name' => 'admin']);

        // Buat user contoh dan assign role
        $user = User::factory()->create([
            'name' => 'Tes User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('photographer');
    }
}
