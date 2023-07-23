<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $lsData = [
            [
                'name' => 'Thanh toán khi nhận hàng',
                'image' => 'donation.png'
            ],
            [
                'name' => 'Stripe',
                'image' => 'stripe.png'
            ],
            [
                'name' => 'Ví của tôi',
                'image' => 'salary.png'
            ],
        ];
        foreach ($lsData as $data) {
            Payment::create($data);
        }
    }
}
