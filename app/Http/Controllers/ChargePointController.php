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

class ChargePointController extends Controller
{
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
			'chargePointId' => (int) $id,
		]);
	}

	public function getChargePointMessages(Request $request, $id): JsonResponse
	{
		$types = MessageType::all();
		$heartbeat = $types->whereIn('type', [
			'Heartbeat',
			'StartTransaction',
			'StopTransaction'
		])->pluck('id');
		$msgs = Message::query()
					   ->where('id_charge_point', $id)
					   ->whereNotIn('id_message_type', $heartbeat)
					   ->orderBy('created_at', 'desc')
					   ->get()
					   ->map(function($element) use ($types){
						   $element->type = $types->where('id', $element->id_message_type)->first()->type;
						   return $element;
					   });

		return response()->json([
			'success' => true,
			'payload' => $msgs,
		]);
	}

	public function getLastStatus($id)
	{
		$types = MessageType::all();
		$status_notification = $types->where('type', 'StatusNotification')->pluck('id');
		$last_status = Message::query()->where('id_charge_point', $id)->where('id_message_type', $status_notification)->orderBy('created_at', 'desc')->first();
		return response()->json([
			'success'     => true,
			'last_status' => json_decode($last_status->payload ?? "", true)['status'] ?? 'NoStatus'
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

	public function delete(Request $request, $id): JsonResponse
	{
		$service = new ChargePointService();
		$service->deleteChargePoint($id);

		return response()->json([
			'success' => true,
			'msg'     => $request->all()
		]);
	}
}
