<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('stock');
            $table->boolean('available')->default(true);
            $table->unsignedBigInteger('ref_seller');
            $table->unsignedBigInteger('ref_category');
            $table->double('price');
            $table->string('main_image');
            $table->string('description');
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->string('side_image')->nullable();
            $table->boolean('blocked')->default(false);
            $table->foreign('ref_seller')->references('id')->on('users');
            $table->foreign('ref_category')->references('id')->on('categories');
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
        Schema::dropIfExists('products');
    }
}
