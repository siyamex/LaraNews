<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logFile)) {
            $content = File::get($logFile);
            // Get last 200 lines
            $lines = array_slice(array_filter(explode("\n", $content)), -200);
            $logs = array_reverse($lines);
        }

        return view('admin.logs.index', compact('logs'));
    }
}
