@extends('layouts.app')

@section('title', 'Dashboard - DailyTask Tracker')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500">{{ now()->format('l, d F Y') }}</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            + Tambah Tugas
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

    <!-- Tasks List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Tugas Hari Ini</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($tasks as $task)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <form action="{{ route('tasks.toggle', $task['id']) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-6 h-6 rounded-full border-2 {{ $task['status'] === 'completed' ? 'bg-green-500 border-green-500' : 'border-gray-300 hover:border-green-500' }}">
                                @if($task['status'] === 'completed')
                                    <svg class="w-4 h-4 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </form>
                        <div>
                            <h3 class="font-medium {{ $task['status'] === 'completed' ? 'text-gray-400 line-through' : 'text-gray-900' }}">{{ $task['title'] }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ substr($task['start_time'], 0, 5) }} - {{ substr($task['end_time'], 0, 5) }}
                                ({{ $task['duration'] }} menit)
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if(isset($task['category']))
                            <span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $task['category']['color'] }}20; color: {{ $task['category']['color'] }}">
                                {{ $task['category']['name'] }}
                            </span>
                        @endif
                        <a href="{{ route('tasks.edit', $task['id']) }}" class="text-gray-400 hover:text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <p>Belum ada tugas hari ini</p>
                    <a href="{{ route('tasks.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Tambah tugas baru</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
