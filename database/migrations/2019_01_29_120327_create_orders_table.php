<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
			$table->string('input_user');
			$table->integer('total');
			$table->date('duedate')->nullable();
			$table->dateTime('respond_reporter')->nullable();
			$table->string('reporter')->nullable();
			$table->string('payment')->nullable();
			$table->dateTime('respond_finance')->nullable();
			$table->string('finance')->nullable();
			$table->integer('flag')->default(1);
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
}
