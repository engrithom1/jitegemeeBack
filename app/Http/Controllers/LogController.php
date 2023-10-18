<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Log;

class LogController extends Controller
{
    public function storeLogs($log){
        Log::create($log);
    }
}
