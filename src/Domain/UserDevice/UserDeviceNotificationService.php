<?php

namespace App\Domain\UserDevice;

use App\Jobs\ProcessDeviceNotificationsJob;
use App\Domain\UserDevice\UserDeviceRepository;
use App\Domain\UserDevice\UserDevice;
use App\Notifications\AppNotification;
use Exception;

/**
* Class UserDeviceNotificationService
* @property UserDeviceRepository $userDeviceRepository
* @package App\Domain\UserDevice
**/
class UserDeviceNotificationService 
{
	public function __construct(
		UserDeviceRepository $userDeviceRepository
	) {
		$this->userDeviceRepository = $userDeviceRepository;
	}

	public function sendNotification($title, $message, $user)
	{
		$user->notify(new AppNotification($title, $message));
	}
	
}
