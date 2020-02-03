<?php

namespace WooSignal\LaraApp\Models;

use Illuminate\Database\Eloquent\Model;

class LaraAppUser extends Model
{

	protected $table = 'la_app_users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'email', 'password', 'app_token', 'is_active'
	];

	public function devices()
	{
		return $this->hasMany('WooSignal\LaraApp\Models\LaUserDevice', 'la_user_id', 'id');
	}
}
