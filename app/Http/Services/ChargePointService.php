<?php

namespace App\Http\Services;

use App\Models\ChargePoint;
use App\Models\ChargePointConfiguration;
use App\Models\Message;
use App\Models\ServerMsgQueue;
use App\Models\Transaction;

class ChargePointService
{

	public function getAllChargePoints()
	{
		return ChargePoint::all();
	}

	public function deleteChargePoint($id)
	{
		$chpnt = ChargePoint::find($id);
		ChargePointConfiguration::query()->where('id_charge_point', $id)->delete();
		ChargePointConfiguration::query()->where('id_charge_point', $id)->delete();
		Message::query()->where('id_charge_point', $id)->delete();
		ServerMsgQueue::query()->where('id_charge_point', $id)->delete();
		Transaction::query()->where('id_charge_point', $id)->delete();
		$chpnt->delete();
	}
}