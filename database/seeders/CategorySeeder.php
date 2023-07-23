<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Category::create(['name' => 'Tất cả', 'image' => 'tatca.png']);
        Category::create(['name' => 'đồ chơi', 'image' => 'maintenance.png']);
        Category::create(['name' => 'phụ tùng', 'image' => 'motorcycle.png']);
        Category::create(['name' => 'vỏ xe', 'image' => 'racing.png']);
        Category::create(['name' => 'nhớt', 'image' => 'engine-oil.png']);
        Category::create(['name' => 'bảo dưỡng', 'image' => 'clutch-disc.png']);
    }
}
