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
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_product');
            $table->decimal('price',8,2);
            $table->integer('quantity');
            $table->decimal('subtotal',8,2);
            $table->timestamps();

            $table->foreign('id_order')->references('id')->on('orders')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_product')->references('id')->on('products')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_orders');
    }
};
