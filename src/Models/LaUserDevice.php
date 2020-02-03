<?php

namespace WooSignal\LaraApp\Models;

use Illuminate\Database\Eloquent\Model;

class LaUserDevice extends Model
{

	protected $table = 'la_user_devices';

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
     * @return WooSignal\LaraApp\Models\LaraAppUser | null
     */
	public function user()
	{
		return $this->hasOne('WooSignal\LaraApp\Models\LaraAppUser', 'id', 'la_user_id');
	}

	/**
     * Pushes a notification to LaraApp
     *
     * @return void
     */
	public function pushNotification($payload, $endpoint)
	{
		$key = config('laraapp.appkey', '');

		if (!empty($key)) {
			$data = [
				'push_token' => $this->push_token, 
				'payload' => $payload
			];
			$this->curlPost("https://thelara.app/" . $endpoint, $data, $key);
		}
	}

	/**
     * Curl POST method
     *
     * @return mixed
     */
	private function curlPost($url, $data=NULL, $token = NULL) 
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($token == null) { return false; }
		if(!empty($data)){
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}		
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/x-www-form-urlencoded',
			'Authorization: Bearer ' . $token
		]);

		$response = curl_exec($ch);

		if (curl_error($ch)) {
			trigger_error('Curl Error:' . curl_error($ch));
		}

		curl_close($ch);
		return $response;
	}
}
