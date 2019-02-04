<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->string('id');
			$table->string('name');
			$table->string('unit_of_measure')->nullable();
			$table->string('qty')->default(0);
			$table->integer('price_per_1_qty')->default(0);
			$table->integer('actual_quantity')->default(0);
			$table->integer('actual_per_price')->default(0);
			$table->integer('actual_price')->default(0);
			$table->string('note')->nullable();
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
        Schema::dropIfExists('order_details');
    }
}
