<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupllierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create(['name' => 'Honda', 'email' => 'honda@honda.com', 'phone' => '00000000', 'image' => 'tatca.png']);
        Supplier::create(['name' => 'Mechelin', 'email' => 'hnda@honda.com', 'phone' => '00000000', 'image' => 'tatca.png']);
        Supplier::create(['name' => 'dddd', 'email' => 'hona@honda.com', 'phone' => '00000000', 'image' => 'tatca.png']);
    }
}
