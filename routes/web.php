<?php

use App\Http\Controllers\ChargePointController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
	return Inertia::render('Welcome', [
		'canLogin'       => Route::has('login'),
		'canRegister'    => false,
		'laravelVersion' => Application::VERSION,
		'phpVersion'     => PHP_VERSION,
	]);
});

Route::get('/dashboard', function(){
	return Inertia::render('Dashboard');
})->middleware([
	'auth',
	'verified'
])->name('dashboard');

Route::middleware('auth')->group(function(){
	Route::get('/clients', [
		ClientsController::class,
		'renderClients'
	])->name('clients');

	Route::get('/get-clients', [
		ClientsController::class,
		'getAllClients'
	])->name('get.clients');

	Route::post('/toggle-client/{id}', [
		ClientsController::class,
		'toggleClient'
	])->name('post.clients');

	Route::get('/profile', [
		ProfileController::class,
		'edit'
	])->name('profile.edit');

	Route::patch('/profile', [
		ProfileController::class,
		'update'
	])->name('profile.update');

	Route::delete('/profile', [
		ProfileController::class,
		'destroy'
	])->name('profile.destroy');

	Route::get('/get-charge-points', [
		ChargePointController::class,
		'get'
	])->name('charge_points.get');

	Route::get('/delete-charge-point/{id}', [
		ChargePointController::class,
		'delete'
	])->name('charge_points.delete');

	Route::get('/charge-point/{id}', [
		ChargePointController::class,
		'getChargePoint'
	])->name('charge_point.get');

	Route::get('/client-transactions/{id}', [
		ClientsController::class,
		'getClientTransactions'
	])->name('charge_point.get');

	Route::get('/get-messages/{id}', [
		ChargePointController::class,
		'getChargePointMessages'
	])->name('msg.get');

	Route::get('/get-last-status/{id}', [
		ChargePointController::class,
		'getLastStatus'
	])->name('status.get');

	Route::get('/get-transactions/{id}', [
		ChargePointController::class,
		'getChargePointTransactions'
	])->name('transactions.get');

	Route::get('/get-configurations/{id}', [
		ChargePointController::class,
		'getChargePointConfigurations'
	])->name('configurations.get');

	Route::get('/get-configuration/{id}', [
		ChargePointController::class,
		'getChargePointConfiguration'
	])->name('configurations.get');

	Route::post('/set-configuration/{id}', [
		ChargePointController::class,
		'setChargePointConfigurations'
	])->name('configurations.post');

	Route::post('/send-charge-point-msg/{id}', [
		ChargePointController::class,
		'sendChargePointMessage'
	])->name('msg.post');
});

require __DIR__ . '/auth.php';
