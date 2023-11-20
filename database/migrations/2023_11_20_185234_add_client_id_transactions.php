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
		Schema::table('transactions', function(Blueprint $table){
			$table->unsignedBigInteger('id_client')->nullable();
			$table->foreign('id_client')->references('id')->on('clients');
			$table->integer('connector_id')->default(1);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('transactions', function(Blueprint $table){
			$table->dropForeign('transactions_id_client_foreign');
			$table->dropColumn('id_client');
		});
	}
};
