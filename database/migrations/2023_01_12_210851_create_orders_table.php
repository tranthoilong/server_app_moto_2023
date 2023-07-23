<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('user_id');
            $table->tinyInteger('status')->default(1);
            $table->integer('total_price')->default(0);
            $table->Text('address')->default(0);
            $table->Text('phone')->nullable();
            $table->String('name')->default(0);
            $table->integer('payment')->default(1);
            $table->Text('note')->nullable();
            $table->integer('ship')->default(0);
            $table->dateTime('booking_date')->default(Carbon::now());
            $table->dateTime('delivery_date')->default(Carbon::now());
            $table->string('total_products');
            $table->string('sub_total')->nullable();
            $table->string('vat')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('discount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
