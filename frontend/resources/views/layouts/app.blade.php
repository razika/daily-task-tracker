<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DailyTask Tracker')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <span class="text-xl font-bold text-blue-600">DailyTask</span>
                    </a>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 {{ request()->routeIs('dashboard') ? 'border-b-2 border-blue-500' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900 {{ request()->routeIs('tasks.*') ? 'border-b-2 border-blue-500 text-gray-900' : '' }}">
                            Tugas
                        </a>
                        <a href="{{ route('reports.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900 {{ request()->routeIs('reports.*') ? 'border-b-2 border-blue-500 text-gray-900' : '' }}">
                            Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 text-center text-sm text-gray-500">
            DailyTask Tracker &copy; {{ date('Y') }}
        </div>
    </footer>
</body>
</html>
