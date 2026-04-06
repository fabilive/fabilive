<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = "";
        
        if (File::exists($logPath)) {
            $file = File::get($logPath);
            // Get last 500 lines for performance
            $lines = explode("\n", $file);
            $logs = implode("\n", array_slice($lines, -500));
        } else {
            $logs = "Log file not found at " . $logPath;
        }

        return view('admin.logs.index', compact('logs'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, "");
        }
        return redirect()->back()->with('success', 'Logs cleared successfully.');
    }
}
