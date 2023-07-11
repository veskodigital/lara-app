<?php

namespace WooSignal\LaraApp\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class LaUser extends Model
{
	use HasApiTokens;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'la_user_device_id', 'email', 'password', 'is_active'
	];

	public function devices()
	{
		return $this->hasMany(\WooSignal\LaraApp\Models\LaUserDevice::class);
	}

	public function apiToken()
	{
		$token = $this->createToken('laraapp_token');
    	return $token->plainTextToken;
	}
}
