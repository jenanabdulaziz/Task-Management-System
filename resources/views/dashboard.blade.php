<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Quick Actions / Navigation -->
            <div class="mb-8 animate-fade-in">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">{{ __('Quick Access') }}</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    
                    <!-- Common for all -->
                    <a href="{{ route('tasks.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-md transition duration-150 p-5 flex items-center group">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3 group-hover:bg-indigo-600 transition">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('My Tasks') }}</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('View All') }}</dd>
                        </div>
                    </a>

                    @if(auth()->user()->can('create', App\Models\Task::class))
                    <a href="{{ route('tasks.create') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-md transition duration-150 p-5 flex items-center group">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3 group-hover:bg-green-600 transition">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('New Task') }}</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Create') }}</dd>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-md transition duration-150 p-5 flex items-center group">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3 group-hover:bg-purple-600 transition">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Users') }}</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Manage') }}</dd>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-md transition duration-150 p-5 flex items-center group">
                        <div class="flex-shrink-0 bg-gray-500 rounded-md p-3 group-hover:bg-gray-600 transition">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Settings') }}</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Configure') }}</dd>
                        </div>
                    </a>
                    @endif

                </div>
            </div>
            
            <!-- Admin Stats -->
            @if($adminStats)
                <div class="mb-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('System Overview') }}</h3>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Users') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">{{ $adminStats['total_users'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Tasks') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">{{ $adminStats['total_tasks'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Active Tasks') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">{{ $adminStats['active_tasks'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0 flex-1" style="margin-inline-start: 1.25rem;">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Overdue Tasks') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">{{ $adminStats['overdue_tasks'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Stats -->
            <div class="mb-8 animate-fade-in delay-100">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('My Tasks Overview') }}</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="glass overflow-hidden rounded-xl card-hover">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Upcoming') }}</dt>
                            <dd class="mt-1 text-3xl font-bold text-indigo-600">{{ $stats['upcoming_count'] }}</dd>
                        </div>
                    </div>
                    <div class="glass overflow-hidden rounded-xl card-hover">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Started') }}</dt>
                            <dd class="mt-1 text-3xl font-bold text-blue-600">{{ $stats['started_count'] }}</dd>
                        </div>
                    </div>
                    <div class="glass overflow-hidden rounded-xl card-hover">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Overdue') }}</dt>
                            <dd class="mt-1 text-3xl font-bold text-red-600">{{ $stats['overdue_count'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in delay-200">
                <!-- Upcoming Tasks List -->
                <div class="glass overflow-hidden rounded-xl shadow-sm">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-100 bg-indigo-50/50">
                        <h3 class="text-lg leading-6 font-semibold text-indigo-900">{{ __('Upcoming Tasks') }}</h3>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse($upcomingTasks as $task)
                            <li class="px-4 py-4 hover:bg-indigo-50/30 transition duration-150">
                                <a href="{{ route('tasks.show', $task) }}" class="block">
                                    <div class="flex justify-between">
                                        <p class="text-sm font-medium text-indigo-700 truncate">{{ $task->title }}</p>
                                        <div class="flex-shrink-0 flex" style="margin-inline-start: 0.5rem;">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->getStatusBadgeClass() }}">
                                                {{ ucfirst($task->status) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-xs text-gray-500">
                                                <svg class="flex-shrink-0 h-4 w-4 text-gray-400" style="margin-inline-end: 0.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ __('Starts') }}: {{ $task->start_date->format('M d') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-4 text-sm text-gray-500 italic">{{ __('No upcoming tasks.') }}</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Started Tasks List -->
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Started Tasks') }}</h3>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @forelse($startedTasks as $task)
                            <li class="px-4 py-4 hover:bg-gray-50">
                                <a href="{{ route('tasks.show', $task) }}" class="block">
                                    <div class="flex justify-between">
                                        <p class="text-sm font-medium text-indigo-600 truncate">{{ $task->title }}</p>
                                        <div class="flex-shrink-0 flex" style="margin-inline-start: 0.5rem;">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->getStatusBadgeClass() }}">
                                                {{ ucfirst($task->status) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" style="margin-inline-end: 0.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Due: {{ $task->end_date->format('M d') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-4 text-sm text-gray-500">{{ __('No started tasks.') }}</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Overdue Tasks List -->
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-red-600">{{ __('Overdue Tasks') }}</h3>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @forelse($overdueTasks as $task)
                            <li class="px-4 py-4 hover:bg-gray-50">
                                <a href="{{ route('tasks.show', $task) }}" class="block">
                                    <div class="flex justify-between">
                                        <p class="text-sm font-medium text-red-600 truncate">{{ $task->title }}</p>
                                        <div class="flex-shrink-0 flex" style="margin-inline-start: 0.5rem;">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ __('Overdue') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-red-500">
                                                <svg class="flex-shrink-0 h-5 w-5 text-red-400" style="margin-inline-end: 0.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                Due: {{ $task->end_date->format('M d') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-4 text-sm text-gray-500">{{ __('No overdue tasks.') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
