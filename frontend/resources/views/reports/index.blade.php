@extends('layouts.app')

@section('title', 'Laporan Harian - DailyTask Tracker')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Laporan Harian</h1>
            <p class="text-slate-500 mt-1">Ringkasan aktivitas hari ini</p>
        </div>
        <a href="{{ route('reports.weekly') }}" class="inline-flex items-center space-x-2 bg-white border border-slate-200 hover:bg-slate-50 px-5 py-2.5 rounded-xl font-medium text-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Laporan Mingguan</span>
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Tugas</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ $summary['total_tasks'] }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Selesai</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $summary['completed_tasks'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pending</p>
                    <p class="text-3xl font-bold text-amber-500 mt-1">{{ $summary['pending_tasks'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Durasi</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ floor($summary['total_duration'] / 60) }}<span class="text-lg">j</span> {{ $summary['total_duration'] % 60 }}<span class="text-lg">m</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    @if($summary['total_tasks'] > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Progres Hari Ini</h2>
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-3 rounded-full transition-all duration-700 ease-out"
                     style="width: {{ ($summary['completed_tasks'] / $summary['total_tasks']) * 100 }}%">
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-sm">
                <span class="text-slate-500">{{ $summary['completed_tasks'] }} dari {{ $summary['total_tasks'] }} tugas selesai</span>
                <span class="font-semibold text-emerald-600">{{ round(($summary['completed_tasks'] / $summary['total_tasks']) * 100) }}%</span>
            </div>
        </div>
    @endif
</div>
@endsection
