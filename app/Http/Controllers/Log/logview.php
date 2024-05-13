<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class logview extends Controller
{
    public $logFileDir='logs/laravel.log';
    public function getSystemLog(){
        $logPath = storage_path($this->logFileDir); // Path to your Laravel log file
        $sysLog = str_replace("\n", "<br>", File::get($logPath));

        return response()->json($sysLog, 200);
    }

    public function clearSystemLog(){
        $logPath = storage_path($this->logFileDir);
        File::put($logPath, "-=MNATA APP LOG=-\n");

        return response()->json(['message'=>'System log cleared'], 200);
    }
}
