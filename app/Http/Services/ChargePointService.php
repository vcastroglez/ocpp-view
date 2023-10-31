<?php

namespace App\Http\Services;

use App\Models\ChargePoint;

class ChargePointService{

	public function getAllChargePoints()
	{
		return ChargePoint::all();
	}
}