<?php

namespace App\Domain\UserDevice;

use App\Domain\UserDevice\UserDeviceRepository;
use App\Domain\UserDevice\UserDevice;
use Exception;

/**
* Class UserDeviceService
* @property UserDeviceRepository $userDeviceRepository
* @package App\Domain\UserDevice
**/
class UserDeviceService 
{
	public function __construct(
		UserDeviceRepository $userDeviceRepository
	) {
		$this->userDeviceRepository = $userDeviceRepository;
	}

	public function registerNewDeviceForUser($user, $deviceMeta)
	{
		$createNewDevice = $this->userDeviceRepository->updateOrCreate(
			[
				'user_id' => $user->id,
				'uuid' => $deviceMeta['uuid'],
			],
			[
				'user_id' => $user->id,
		        'uuid' => $deviceMeta['uuid'],
		        'model' => $deviceMeta['model'],
		        'brand' => $deviceMeta['brand'],
		        'manufacturer' => $deviceMeta['manufacturer'],
		        'version' => $deviceMeta['version'],
		        'push_token' => isset($deviceMeta['push_token']) ? $deviceMeta['push_token'] : '',
		        'is_active' => 1,
			]
		);

		return $createNewDevice;
	}
	
}