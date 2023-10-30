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
		Schema::dropIfExists('connections');
		Schema::create('charge_points', function(Blueprint $table){
			$table->id();
			$table->string('uuid')->unique();
			$table->text('note')->nullable();
			$table->unsignedBigInteger('last_meter_value')->default(0);
			$table->unsignedBigInteger('last_transaction_id')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('charge_points');
	}
};
