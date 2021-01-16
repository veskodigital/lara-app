<?php

namespace WooSignal\LaraApp\Models;

use Illuminate\Database\Eloquent\Model;

class LaAppRequest extends Model
{

	protected $table = 'la_app_requests';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'device_id','request_type','ip'
	];

	public function device()
	{
		return $this->hasOne('WooSignal\LaraApp\Models\LaUserDevice', 'id', 'device_id');
	}
}
