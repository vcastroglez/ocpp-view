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
		Schema::create('messages', function(Blueprint $table){
			$table->id();
			$table->unsignedBigInteger('id_charge_point');
			$table->foreign('id_charge_point')->on('charge_points')->references('id');
			$table->unsignedBigInteger('id_message_type');
			$table->foreign('id_message_type')->on('message_types')->references('id');
			$table->text('payload');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('messages');
	}
};
