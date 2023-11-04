<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class registerUser extends Command{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:register-user {username} {password}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$username = $this->argument('username');
		$pass = $this->argument('password');
		$user = new User();
		$user->name = $username;
		$user->email = $username.'@gmail.com';
		$user->email_verified_at = Carbon::now();
		$user->password = $pass;
		$user->save();

		echo "User: {$user->email} created".PHP_EOL;

	}
}
