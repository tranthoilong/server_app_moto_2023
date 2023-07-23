<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name'=>'walk-in-customer','gender'=>1,'email'=>'walkincustomer@admin.com','phone'=>'000000000','password'=>'$2y$10$J.yc2AbQcuqP03K8Y7Cr/eFr15zuXM.3Hd3s5nd5gLyMuXXhDgeUS','created_at'=>'2023-07-13 13:32:11','updated_at'=>'2023-07-13 13:32:11']);
        User::create(['name'=>'Administrator','gender'=>1,'email'=>'admin@admin.com','phone'=>'0398645613','password'=>'$2y$10$J.yc2AbQcuqP03K8Y7Cr/eFr15zuXM.3Hd3s5nd5gLyMuXXhDgeUS','created_at'=>'2023-07-13 13:32:11','updated_at'=>'2023-07-13 13:32:11']);
    }
}
