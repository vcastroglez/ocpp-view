<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('server_msg_queues', function(Blueprint $table){
			$table->id();
			$table->unsignedBigInteger('id_charge_point');
			$table->foreign('id_charge_point')->references('id')->on('charge_points');
			$table->text('payload');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('server_msg_queues');
	}
};
