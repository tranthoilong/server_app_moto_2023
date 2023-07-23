<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Banner::create(['name' => 'none', 'image' => 'banner/1.jpg']);
        // Banner::create(['name' => 'none', 'image' => 'banner/2.jpg']);
        // Banner::create(['name' => 'none', 'image' => 'banner/3.jpg']);
        Banner::create(['name' => 'none', 'image' => 'banner/4.jpg']);
        Banner::create(['name' => 'none', 'image' => 'banner/5.jpg']);
        Banner::create(['name' => 'none', 'image' => 'banner/6.jpg']);
        // Banner::create(['name' => 'none', 'image' => 'banner/7.jpg']);
    }
}
