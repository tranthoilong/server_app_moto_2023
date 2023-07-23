<?php

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->String('name');
            $table->String('image');
            // $table->foreignId('users_id')->constrained();
            // $table->foreignId('orders_id')->constrained();
            // $table->string('payment_methods');
            // $table->string('image');
            // $table->decimal('amount');
            // $table->timestamp('payment_of_day');
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
        Schema::dropIfExists('payments');
    }
};
