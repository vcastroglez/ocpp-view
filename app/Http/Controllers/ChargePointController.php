<?php

namespace App\Http\Controllers;

use App\Http\Services\ChargePointService;
use App\Models\Message;
use App\Models\MessageType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChargePointController extends Controller{
	public function get(Request $request)
	{
		$service = new ChargePointService();

		return response()->json([
			'success' => true,
			'payload' => $service->getAllChargePoints()
		]);
	}

	public function getChargePoint(Request $request, $id)
	{
		return Inertia::render('CentralSystem/ChargePoint', [
			'chargePointId' => (int)$id,
		]);
	}

	public function getChargePointMessages(Request $request, $id)
	{
		$types = MessageType::all();
		$heartbeat = $types->where('type', 'Heartbeat')->first();
		$msgs = Message::query()->where('id_charge_point', $id)->where('id_message_type', '!=', $heartbeat->id)->orderBy('created_at','desc')->get()->map(function($element) use ($types){
			$element->type = $types->where('id', $element->id_message_type)->first()->type;
			return $element;
		});

		return response()->json([
			'success' => true,
			'payload' => $msgs
		]);
	}
}
