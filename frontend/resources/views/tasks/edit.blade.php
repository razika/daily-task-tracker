@extends('layouts.app')

@section('title', 'Edit Tugas - DailyTask Tracker')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Tugas</h1>
        <p class="text-gray-500">Update detail tugas</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('tasks.update', $task['id']) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Judul Tugas *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task['title']) }}" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $task['description'] ?? '') }}</textarea>
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" id="category_id"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}" {{ old('category_id', $task['category_id'] ?? '') == $category['id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal *</label>
                <input type="date" name="date" id="date" value="{{ old('date', $task['date']) }}" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Jam Mulai *</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', substr($task['start_time'], 0, 5)) }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">Jam Selesai *</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', substr($task['end_time'], 0, 5)) }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Durasi (menit) *</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration', $task['duration']) }}" min="1" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="pending" {{ old('status', $task['status']) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status', $task['status']) === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Tugas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('start_time').addEventListener('change', calculateDuration);
    document.getElementById('end_time').addEventListener('change', calculateDuration);

    function calculateDuration() {
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;

        if (start && end) {
            const [startHours, startMinutes] = start.split(':').map(Number);
            const [endHours, endMinutes] = end.split(':').map(Number);

            let duration = (endHours * 60 + endMinutes) - (startHours * 60 + startMinutes);
            if (duration < 0) duration += 24 * 60;

            document.getElementById('duration').value = duration;
        }
    }
</script>
@endsection
