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
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('name');
            $table->string('image')->default('none');
            $table->text('description');
            $table->integer('number')->default(rand(1, 10000));
            $table->integer('price')->default(rand(50, 300) * 1000);
            $table->integer("like")->default(rand(0, 9999));
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Thêm khóa ngoại category_id
            $table->foreign('category_id')->references('id')->on('categories');

            // Thêm khóa ngoại supplier_id
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
