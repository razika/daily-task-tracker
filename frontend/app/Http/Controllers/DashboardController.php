<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $apiUrl = config('services.api.url', 'http://backend:8080');

        try {
            $response = Http::get("{$apiUrl}/api/tasks", ['date' => $today]);
            $tasks = $response->json();

            $summaryResponse = Http::get("{$apiUrl}/api/reports/summary", ['date' => $today]);
            $summary = $summaryResponse->json();
        } catch (\Exception $e) {
            $tasks = [];
            $summary = [
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'pending_tasks' => 0,
                'total_duration' => 0,
            ];
        }

        return view('dashboard', compact('tasks', 'summary'));
    }
}
