<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flags', function (Blueprint $table) {
            $table->integer('id')->unique();
			$table->String('status');
            $table->timestamps();
        });
				
		$data = array(
			array( 'id' => 0, 'status' => "Decline" ),
			array( 'id' => 1, 'status' => "Pending" ),
			array( 'id' => 2, 'status' => "Approve" ),
			array( 'id' => 3, 'status' => "Deliver" ),
		);
		DB::table('flags')->insert($data); // Query Builder approach
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flags');
    }
}
