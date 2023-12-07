<?php

namespace App\Http\Controllers;

use App\Http\Services\ChargePointService;
use App\Models\ChargePointConfiguration;
use App\Models\Message;
use App\Models\MessageType;
use App\Models\ServerMsgQueue;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ChargePointController extends Controller{
	public function get(Request $request): JsonResponse
	{
		$service = new ChargePointService();

		return response()->json([
			'success' => true,
			'payload' => $service->getAllChargePoints()
		]);
	}

	public function getChargePoint(Request $request, $id): Response
	{
		return Inertia::render('CentralSystem/ChargePoint', [
			'chargePointId' => (int)$id,
		]);
	}

	public function getChargePointMessages(Request $request, $id): JsonResponse
	{
		$types = MessageType::all();
		$heartbeat = $types->where('type', 'Heartbeat')->first();
		$msgs = Message::query()->where('id_charge_point', $id)->where('id_message_type', '!=', $heartbeat->id)->orderBy('created_at', 'desc')->get()->map(function($element) use ($types){
			$element->type = $types->where('id', $element->id_message_type)->first()->type;
			return $element;
		});

		return response()->json([
			'success' => true,
			'payload' => $msgs
		]);
	}

	public function getChargePointTransactions(Request $request, $id): JsonResponse
	{
		$transactions = Transaction::query()->where('id_charge_point', $id)->orderBy('id', 'DESC')->with('client')->get();

		return response()->json([
			'success' => true,
			'payload' => $transactions
		]);
	}

	public function getChargePointConfigurations(Request $request, $id): JsonResponse
	{
		return response()->json([
			'success' => true,
			'payload' => ChargePointConfiguration::query()->where('id_charge_point', $id)->get()
		]);
	}

	public function getChargePointConfiguration(Request $request, $id): JsonResponse
	{
		$key = $request->get('configuration');

		$model = new ServerMsgQueue();
		$model->id_charge_point = $id;
		$model->payload = [
			'action' => 'GetConfiguration',
			'text'   => [
				'key' => [$key]
			]
		];
		$model->user_id = Auth::user()->id;
		$model->message_type = 2;
		$model->save();

		return response()->json([
			'success' => true,
			'payload' => ChargePointConfiguration::query()->where('id_charge_point', $id)->get()
		]);
	}

	public function setChargePointConfigurations(Request $request, $id): JsonResponse
	{
		$model = new ServerMsgQueue();
		$model->id_charge_point = $id;
		$model->payload = [
			'action' => 'ChangeConfiguration',
			'text'   => [
				'key'   => $request->get('key'),
				'value' => $request->get('value')
			]
		];
		$model->user_id = Auth::user()->id;
		$model->message_type = 2;
		$model->save();
		return response()->json([
			'success' => true,
			'msg'     => $request->all()
		]);
	}

	public function sendChargePointMessage(Request $request, $id): JsonResponse
	{
		$model = new ServerMsgQueue();
		$model->id_charge_point = $id;
		$model->payload = [
			'action' => 'TriggerMessage',
			'text'   => [
				'requestedMessage' => $request->get('requestedMessage'),
				'connectorId'      => $request->get('connectorId')
			]
		];
		$model->user_id = Auth::user()->id;
		$model->message_type = $request->get('type');
		$model->save();

		return response()->json([
			'success' => true,
			'msg'     => $model->id
		]);
	}
}
