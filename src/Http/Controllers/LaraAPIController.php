<?php

namespace VeskoDigital\LaraApp\Http\Controllers;

use Symfony\Component\Console\Exception\RuntimeException;
use VeskoDigital\LaraApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use VeskoDigital\LaraApp\Http\Requests\PushTokenRequest;
use VeskoDigital\LaraApp\Http\Requests\PushNotificationsUpdateRequest;
use Artisan;
use File;
use DB;

class LaraAPIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      
    }

    /**
     * Post a command to artisan
     *
     * @return Response
     */
    public function postCommmand(Request $request)
    {
        $valCommand = $request->command;

        try {
            $exitCode = Artisan::call($valCommand);
            return response()->json(['result' => Artisan::output()]);   
        } catch (RuntimeException $e) {
            return response()->json(['result' => $e->getMessage()]);
        }
        return response()->json(['result' => '']);
    }

    /**
     * Fetch all artisan commands available
     *
     * @return Response
     */
    public function getCommands()
    {
         $arrNewPayload = [];
         $artisanCommands = Artisan::all();
         foreach($artisanCommands as $key => $command) {
            if (str_contains($key, ':')) {
                $keyFromExplode = explode(":", $key);
                if (!empty($keyFromExplode)) {
                    $keyFromExplode = array_shift($keyFromExplode);
                }
                $arrNewPayload[$keyFromExplode][] = $key;
                continue;
            }
            $arrNewPayload['default'][] = $key;
        }
        return response()->json($arrNewPayload);
    }

    /**
     * Get all app routes
     *
     * @return array
     */
    public function getRoutes() 
    {
        $app = app();
        $routes = $app->routes->getRoutes();

        $arrNewPayload = [];
        foreach ($routes as $route) {
            $arrNewPayload[] = [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'method' => $route->getActionMethod(),
                'methods' => $route->methods(),
                'controller' => $route->getActionName()
            ];
        }
        return $arrNewPayload;
    }

    /**
     * Get app info.
     *
     * @return Response
     */
    public function getInfo()
    {
        $appName = config('laraapp.app_name', 'Laravel App');
        
        $envVars = collect($_ENV)->map(function($item, $key) {
            $typeFound = gettype($item);
            if (in_array(strtolower($item), ['true', 'false'])) {
                $typeFound = 'boolean';
            }
            return ['key' => $key, 'value' => $item,  'type' => $typeFound];
        })->values();

        return response()->json([
            'name' => $appName, 
            'environment' => \App::environment(), 
            'laravel_version' => \App::version(),
            'maintenance_mode' => \App::isDownForMaintenance(),
            'env' => $envVars,
            'php_version' => phpversion()
        ]);
    }

    /**
     * Return all the tables in the app
     *
     * @return mixed
     */
    public function getTables()
    {
        $tables = DB::select('SHOW TABLES');

        if (empty($tables)) {
            return response()->json(['status' => 200, 'data' => []]);
        }

        $tables = collect(DB::select('SHOW TABLES'))->map(function($table) {
                $firstProp = current( (Array) $table );
                
                return [
                    'name' => $firstProp, 
                    'records' => DB::table($firstProp)->count(),
                    'size' => ''
                ];
            })->toArray();

        return response()->json(['status' => 200, 'data' => $tables]);
    }

    /**
     * Return all the tables in the app
     *
     * @return mixed
     */
    public function getTable(Request $request)
    {
        $tableName = $request->table;
        $tables = DB::table($tableName)->get();

        if ($tables->isEmpty()) {
            return response()->json(['status' => 200, 'data' => []]);
        }    

        $columns = array_keys((array) $tables[0]);

        $data = $tables->map(function($data) {
            $vars = get_object_vars($data);
            return array_values($vars);
        })->toArray();

        $payload = [
            'columns' => $columns,
            'tables' => $data
        ];

        return response()->json(['status' => 200, 'data' => $payload]);
    }

    /**
     * Get all file names for the logs created
     *
     * @return mixed
     */
    public function getLogs()
    {
        $defaultVal = config('laraapp.logging_default', null);
        if (is_null($defaultVal)) {
            return [];
        }
        if (in_array($defaultVal, ['stack','single','daily'])) {
            return $this->returnAllLogFileNames();
        } else {
            return [];
        }
    }

    /**
     * Returns all the logs in the storage path
     *
     * @return mixed
     */
    public function returnAllLogFileNames()
    {
        $allFiles = File::files('../' . config('laraapp.storage_path', 'storage/logs') . '/');
        $arrLogs = [];
        foreach ($allFiles as $value) {
            $fileName = $value->getFileName();
            $size = File::size('../' . config('laraapp.storage_path', 'storage/logs') . '/' . $fileName);
            $arrLogs[] = [
                'name' => $fileName,
                'size' => $size,
                'readable_size' => $this->formatBytes($size)
            ];
        }
        return $arrLogs;
    }

    private function formatBytes($bytes) 
    {
        if ($bytes > 0) {
            $i = floor(log($bytes) / log(1024));
            $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
        } else {
            return 0;
        }
    }

    /**
     * Returns the contents for a given log file
     *
     * @return String
     */
    public function showLogDetail($logFile)
    {
        $fileData = File::get('../' . config('laraapp.storage_path', 'storage/logs') .'/' . $logFile);
        return response()->json(['status' => '200', 'data' => $fileData]);
    }

    /**
     * Stores the users push token
     *
     * @return mixed
     */
    public function storeToken(PushTokenRequest $request)
    {
        $userDevice = $request->input('udevice');
        
        $valid = $request->validated();
        $pushToken = $valid['push_token'];

        if (!is_null($userDevice)) {
            $this->userDevice->update([
                'push_token' => $pushToken
            ]);

            return response()->json(['status' => 200]);
        }
        return response()->json(['status' => 500]);
    }

    /**
     * Updates the users push notification settings
     *
     * @return mixed
     */
    public function updateNotifications(PushNotificationsUpdateRequest $request)
    {
        $valid = $request->validated();
        $userDevice = $request->input('udevice');
        $pushSettings = $valid['push_settings'];

        if (is_null($userDevice)) {
            return response()->json(['status' => 500]);
        }

        $userDevice->update([
            'push_settings' => json_encode($pushSettings)
        ]);
        
        return response()->json(['status' => 200, 'result' => $pushSettings]);
    }

    public function getUsersQueryByDays(Request $request)
    {
        $users = config('laraapp.user', \App\Models\User::class);

        $days = $request->days;
        preg_match('/([0-9]+)/', $days, $matches);

        $dateNow = now();

        $prev_d = strtotime("-" . $days, strtotime($dateNow));
        $newStartDate = date("Y-m-d", $prev_d);

        $results = $users::whereBetween('created_at', [$newStartDate, $dateNow])->count();

        $days = preg_match('/([0-9]+)/', $days);
        $regexDaysMatch = $matches[0];

        $avgUsersPerDay = intval($results / $regexDaysMatch);

        return response()->json(['status' => 200, 'data' => [
            'total_users' => $results,
            'avg_daily' => $avgUsersPerDay
        ]]);
    }

    /**
     * Shows a devices current notification settings
     *
     * @return mixed
     */
    public function showNotifications(Request $request)
    {
        $userDevice = $request->input('udevice');
        $settings = $userDevice->push_settings;

        return response()->json(['status' => 200, 'result' => json_decode($settings, true)]);
    }


    private function setEnv($key, $value)
    {
        $path = app()->environmentFilePath();
        if (!file_exists($path)) {
            return;
        }

        $old = env($key);
        if (is_bool(env($key))) {
            $old = env($key) ? 'true' : 'false';
        }
        
        if (filter_var($value, FILTER_VALIDATE_INT) == false) {
            $keyValue = "$key=\"{$value}\"";
            file_put_contents($path, str_replace(
                "$key='{$old}'", $keyValue, file_get_contents($path)
            ));

            file_put_contents($path, str_replace(
                "$key=\"{$old}\"", $keyValue, file_get_contents($path)
            ));

            file_put_contents($path, str_replace(
                "$key={$old}", $keyValue, file_get_contents($path)
            ));
        } else {
            file_put_contents($path, str_replace(
                "$key=".$old, "$key={$value}", file_get_contents($path)
            ));
        }
    }

    public function postSetEnv(Request $request)
    {
        $key = $request->key;
        $value = $request->value;
        $this->setEnv($key, $value);
        
        return response()->json([
            'status' => 200
        ]);
    }
}