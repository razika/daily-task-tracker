@extends('layouts.app')

@section('title', 'Laporan Harian - DailyTask Tracker')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Harian</h1>
            <p class="text-gray-500">Ringkasan aktivitas hari ini</p>
        </div>
        <a href="{{ route('reports.weekly') }}" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg font-medium">
            Lihat Laporan Mingguan
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Tugas</div>
            <div class="text-3xl font-bold text-gray-900">{{ $summary['total_tasks'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Selesai</div>
            <div class="text-3xl font-bold text-green-600">{{ $summary['completed_tasks'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Pending</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $summary['pending_tasks'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Durasi</div>
            <div class="text-3xl font-bold text-blue-600">
                {{ floor($summary['total_duration'] / 60) }}j {{ $summary['total_duration'] % 60 }}m
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    @if($summary['total_tasks'] > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Progres Hari Ini</h2>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-green-500 h-4 rounded-full transition-all duration-500"
                     style="width: {{ ($summary['completed_tasks'] / $summary['total_tasks']) * 100 }}%">
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500">
                {{ $summary['completed_tasks'] }} dari {{ $summary['total_tasks'] }} tugas selesai
                ({{ round(($summary['completed_tasks'] / $summary['total_tasks']) * 100) }}%)
            </div>
        </div>
    @endif
</div>
@endsection
