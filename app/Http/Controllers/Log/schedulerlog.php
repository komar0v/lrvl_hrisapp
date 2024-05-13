<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class schedulerlog extends Controller
{
    public function getScheduleList()
    {
        // Run the schedule:list command
        $output = Artisan::call('schedule:list');

        // Get the command output
        $output = Artisan::output();

        // Return the output as a response
        return response()->json(str_replace(["\r", "\n", "  "], "", $output));
    }
}
