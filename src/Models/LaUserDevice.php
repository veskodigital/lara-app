<?php

namespace WooSignal\LaraApp\Models;

use Illuminate\Database\Eloquent\Model;

class LaUserDevice extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'la_user_id', 'name', 'version', 'push_token', 'is_active', 'display_name', 'uuid', 'push_settings'
	];

	/**
     * Returns User model for LaraApp
     *
     * @return WooSignal\LaraApp\Models\LaUser | null
     */
	public function user()
	{
		return $this->belongsTo(\WooSignal\LaraApp\Models\LaUser::class);
	}

	/**
     * Returns app requests for a device.
     *
     * @return WooSignal\LaraApp\Models\LaAppRequest | null
     */
	public function appRequests()
	{
		return $this->hasMany(\WooSignal\LaraApp\Models\LaAppRequest::class);
	}
}
