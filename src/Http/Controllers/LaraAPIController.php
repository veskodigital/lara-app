<?php

namespace WooSignal\LaraApp\Http\Controllers;

use Symfony\Component\Console\Exception\RuntimeException;
use WooSignal\LaraApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WooSignal\LaraApp\Http\Requests\PushTokenRequest;
use WooSignal\LaraApp\Http\Requests\PushNotificationsUpdateRequest;
use Artisan;
use File;

class LaraAPIController extends Controller
{

    private $userDevice;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userDevice = $request->input('udevice');
            
            return $next($request);
        });
    }

    /**
     * Post a command to artisan
     *
     * @return void
     */
    public function postCommmand(Request $request)
    {
        $valCommand = $request->input('command');
        $output = '';
        try {
            $exitCode = Artisan::call($valCommand);
            $output .= Artisan::output();
            return response()->json(['result' => $output]);   
        } catch (RuntimeException $e) {
            $output .= $e->getMessage();
            return response()->json(['result' => $output]);   
        }
    }

    /**
     * Delete all logs in storage
     *
     * @return void
     */
    public function deleteAllLogs(Request $request)
    {
        $files =   File::allFiles('../' . config('laraapp.storage_path', 'storage/logs'));
        File::delete($files);
        return response()->json(['status' => '200']);
    }

    /**
     * Delete all logs in storage
     *
     * @return void
     */
    public function deleteLog(Request $request)
    {
        $logName = $request->logName;
        $files =   File::allFiles('../' . config('laraapp.storage_path', 'storage/logs'));
        $hasFile = File::exists('../' . config('laraapp.storage_path', 'storage/logs/') . $logName);
        if ($hasFile) {
            File::delete('../' . config('laraapp.storage_path', 'storage/logs/') . $logName);
        }
        return response()->json(['status' => '200']);
    }

    /**
     * Fetch all artisan commands available
     *
     * @return void
     */
    public function getCommands()
    {
     $arrNewPayload = [];
     $artisanCommands = Artisan::all();
     foreach($artisanCommands as $key => $command)
     {
        if ($this->strContains(':', $key)) {
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
 * Check if a String contains a value
 *
 * @return void
 */
private function strContains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}

/**
 * Get all app routes
 *
 * @return void
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
 * Get Info
 *
 * @return mixed
 */
public function getInfo()
{
    $appName = config('laraapp.app_name', 'Laravel App');
    return response()->json([
        'name' => $appName, 
        'environment' => \App::environment(), 
        'version' => \App::version()
    ]);
}

/**
 * Return all the tables in the app
 *
 * @return mixed
 */
public function getTables()
{
    $tables = array_map('reset', \DB::select('SHOW TABLES'));
    $arrNewPayload = [];
    if (!empty($tables)) {
        foreach ($tables as $table) {
            $arrNewPayload[] = [
                'name' => $table,
                'records' => \DB::table($table)->count()
            ];
        }
    }
    return $arrNewPayload;
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
        $arrLogs[] = [
            'name' => $fileName,
            'size' => File::size('../' . config('laraapp.storage_path', 'storage/logs') . '/' . $fileName)
        ];
    }
    return $arrLogs;
}

public function chartUsers()
{
    $users = config('laraapp.user', \App\User::class);

    $thisStart = date("Y-m-d", strtotime('monday this week'));
    $thisEnd = date("Y-m-d", strtotime("sunday this week"));

    $prev_d = strtotime("-1 week -1 day");
    $prevStart = date("Y-m-d", strtotime("monday this week",$prev_d)); 
    $prevEnd = date("Y-m-d", strtotime("sunday this week",$prev_d)); 

    $prevWeek = $users::whereBetween('created_at', [$prevStart, $prevEnd])->count();
    $currentWeek = $users::whereBetween('created_at', [$thisStart, $thisEnd])->count();
    
    $subWeekVal = $currentWeek - $prevWeek;

    $subWeekVal = $subWeekVal != 0 ? $subWeekVal : 1;
    $prevWeek = $prevWeek != 0 ? $prevWeek : 1;
    $diffVal = $subWeekVal / $prevWeek;

    $diff = number_format($diffVal * 100, 2);

    return response()->json([
        'prev' => $users::whereBetween('created_at', [$prevStart, $prevEnd])->count(),
        'current' => $users::whereBetween('created_at', [$thisStart, $thisEnd])->count(),
        'diff' => $diff,
        'data' => [
            0 => $users::whereDate('created_at', date('Y-m-d', strtotime('monday this week')))->count(),
            1 => $users::whereDate('created_at', date('Y-m-d', strtotime('tuesday this week')))->count(),
            2 => $users::whereDate('created_at', date('Y-m-d', strtotime('wednesday this week')))->count(),
            3 => $users::whereDate('created_at', date('Y-m-d', strtotime('thursday this week')))->count(),
            4 => $users::whereDate('created_at', date('Y-m-d', strtotime('friday this week')))->count(),
            5 => $users::whereDate('created_at', date('Y-m-d', strtotime('saturday this week')))->count(),
            6 => $users::whereDate('created_at', date('Y-m-d', strtotime('sunday this week')))->count(),
        ]
    ]);
}

/**
 * Returns the contents for a given log file
 *
 * @return String
 */
public function showLogDetail($logFile)
{
    return File::get('../' . config('laraapp.storage_path', 'storage/logs') .'/' . $logFile);
}

/**
 * Stores the users push token
 *
 * @return mixed
 */
public function storeToken(PushTokenRequest $request)
{
    $valid = $request->validated();
    $pushToken = $valid['push_token'];

    if (!is_null($this->userDevice)) {
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
    $pushSettings = $valid['push_settings'];

    if (!is_null($this->userDevice)) {
        $this->userDevice->update([
            'push_settings' => $pushSettings
        ]);
        $rsp = json_decode($pushSettings, true);
        $rsp['status'] = 200;
        return response()->json($rsp);
    }
    return response()->json(['status' => 500]);
}

/**
 * Shows a devices current notification settings
 *
 * @return mixed
 */
public function showNotifications(Request $request)
{
    $settings = $this->userDevice->push_settings;

    return response()->json(['status' => 200, 'result' => $settings]);
}

}