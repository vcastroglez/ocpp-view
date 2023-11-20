<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientsController extends Controller{
	public function renderClients(): Response
	{
		return Inertia::render('Clients');
	}

	public function getAllClients(): JsonResponse
	{
		return response()->json(['payload' => Client::all()]);
	}

	public function toggleClient(Request $request, $id)
	{
		Client::query()->find($id)->update(['authorized' => $request->get('authorize')]);
		return response()->json(['payload' => Client::all()]);
	}

	public function getClientTransactions(Request $request, $id)
	{
		$transactions = Transaction::query()->where('id_client', $id)->get();
		return Inertia::render('CentralSystem/Client', [
			'transactions' => $transactions,
			'client'       => Client::query()->find($id)
		]);
	}
}
