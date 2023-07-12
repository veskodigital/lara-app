<?php

namespace VeskoDigital\LaraApp\Models;

use Illuminate\Database\Eloquent\Model;

class LaAppRequest extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'la_user_device_id','request_type','ip'
	];

	public function device()
	{
		return $this->belongsTo(\VeskoDigital\LaraApp\Models\LaUserDevice::class);
	}
}
