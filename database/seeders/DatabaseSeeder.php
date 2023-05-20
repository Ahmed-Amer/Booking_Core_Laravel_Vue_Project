<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(20)->create();
        
        $this->call([
            BookableSeeder::class,
        ]);

        $this->call([
            BookingSeeder::class,
        ]);

        $this->call([
            ReviewSeeder::class,
        ]);
        
        
        //\App\Models\Booking::factory(20)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
