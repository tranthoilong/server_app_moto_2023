<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\BannerSeeder;
use Database\Seeders\PaymentSeeder;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([SupllierSeeder::class, CategorySeeder::class, ProductSeeder::class, BannerSeeder::class, UserSeeder::class, PaymentSeeder::class]);
        // $this->call([CategorySeeder::class, SupllierSeeder::class, ProductSeeder::class]);
    }
}
