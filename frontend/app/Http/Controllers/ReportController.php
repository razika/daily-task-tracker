<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.api.url', 'http://backend:8080');
    }

    public function index()
    {
        try {
            $summary = Http::get("{$this->apiUrl}/api/reports/summary")->json();
        } catch (\Exception $e) {
            $summary = [
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'pending_tasks' => 0,
                'total_duration' => 0,
            ];
        }

        return view('reports.index', compact('summary'));
    }

    public function weekly(Request $request)
    {
        $startDate = $request->get('start_date', now()->subWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        try {
            $report = Http::get("{$this->apiUrl}/api/reports/weekly", [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ])->json();
        } catch (\Exception $e) {
            $report = [
                'daily_summaries' => [],
                'total_tasks' => 0,
                'total_duration' => 0,
            ];
        }

        return view('reports.weekly', compact('report', 'startDate', 'endDate'));
    }
}
