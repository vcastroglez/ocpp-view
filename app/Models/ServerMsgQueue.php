<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerMsgQueue extends Model
{
    use HasFactory;

	protected $casts = [
		'payload'=>'json'
	];
}
