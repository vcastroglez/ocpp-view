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
		Schema::table('server_msg_queues', function(Blueprint $table){
			$table->unsignedBigInteger('user_id')->default(1);
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('message_type')->default(3);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('server_msg_queues', function(Blueprint $table){
			$table->dropForeign('server_msg_queues_user_id_foreign');
			$table->dropColumn('user_id');
//			$table->dropColumn('message_type');
		});
	}
};
