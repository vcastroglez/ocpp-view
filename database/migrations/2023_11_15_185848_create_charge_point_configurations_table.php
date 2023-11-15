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
		Schema::create('charge_point_configurations', function(Blueprint $table){
			$table->id();
			$table->unsignedBigInteger('id_charge_point');
			$table->foreign('id_charge_point')->references('id')->on('charge_points');
			$table->string('key');
			$table->boolean('readonly')->default(0);
			$table->string('value')->nullable();
			$table->unique(['id_charge_point','key']);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('charge_point_configurations');
	}
};
