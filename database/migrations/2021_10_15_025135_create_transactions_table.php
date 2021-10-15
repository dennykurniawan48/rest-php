<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code_transaction');
            $table->unsignedBigInteger('ref_seller');
            $table->unsignedBigInteger('ref_buyer');
            $table->unsignedBigInteger('ref_product');
            $table->timestamps();

            $table->foreign('ref_seller')->references('id')->on('users');
            $table->foreign('ref_buyer')->references('id')->on('users');
            $table->foreign('ref_product')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
