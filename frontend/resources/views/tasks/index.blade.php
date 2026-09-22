@extends('layouts.app')

@section('title', 'Daftar Tugas - DailyTask Tracker')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Tugas</h1>
            <p class="text-gray-500">Kelola tugas harian kamu</p>
        </div>
        <div class="flex items-center space-x-4">
            <form action="{{ route('tasks.index') }}" method="GET" class="flex items-center space-x-2">
                <input type="date" name="date" value="{{ $date }}" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">Filter</button>
            </form>
            <a href="{{ route('tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                + Tambah Tugas
            </a>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tasks as $task)
                    <tr class="{{ $task['status'] === 'completed' ? 'bg-gray-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('tasks.toggle', $task['id']) }}" method="POST">
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
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium {{ $task['status'] === 'completed' ? 'text-gray-400 line-through' : 'text-gray-900' }}">
                                {{ $task['title'] }}
                            </div>
                            @if(!empty($task['description']))
                                <div class="text-sm text-gray-500">{{ Str::limit($task['description'], 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ substr($task['start_time'], 0, 5) }} - {{ substr($task['end_time'], 0, 5) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $task['duration'] }} menit
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(isset($task['category']))
                                <span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $task['category']['color'] }}20; color: {{ $task['category']['color'] }}">
                                    {{ $task['category']['name'] }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('tasks.edit', $task['id']) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada tugas untuk tanggal ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
