<?php

namespace WooSignal\LaraApp\Observers;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserObserver
 *
 * @property LaUserDeviceService $laUserDeviceService
 * @package WooSignal\LaraApp\Observers\UserObserver
 */
class UserObserver
{
    public function __construct()
    {
        $this->laUserDeviceService = resolve(\WooSignal\LaraApp\Http\Services\LaUserDeviceService::class);
    }

    public function created(Eloquent $model)
    {
        $appName = config('app.name');
        $this->laUserDeviceService->broadcastNotification('observer_created_user', [
            'message' => "[{$appName}] User created"
        ]);
    }
}
