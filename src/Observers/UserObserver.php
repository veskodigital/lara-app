<?php

namespace VeskoDigital\LaraApp\Observers;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserObserver
 *
 * @property LaUserDeviceService $laUserDeviceService
 * @package VeskoDigital\LaraApp\Observers\UserObserver
 */
class UserObserver
{
    public function __construct()
    {
        $this->laUserDeviceService = resolve(\VeskoDigital\LaraApp\Http\Services\LaUserDeviceService::class);
    }

    public function created(Eloquent $model)
    {
        $appName = config('app.name');
        $this->laUserDeviceService->broadcastNotification('observer_created_user', [
            'message' => "[{$appName}] User created"
        ]);
    }
}
