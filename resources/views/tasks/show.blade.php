<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <div class="flex gap-2">
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                        {{ __('Edit') }}
                    </a>
                    
                    @if(!in_array($task->status, ['completed', 'cancelled']))
                        <form method="POST" action="{{ route('tasks.complete', $task) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                                {{ __('Mark as Completed') }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('tasks.cancel', $task) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                                {{ __('Cancel Task') }}
                            </button>
                        </form>
                    @endif
                @endcan
                <a href="{{ route('tasks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Task Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $task->getStatusBadgeClass() }}">
                                    {{ __(ucfirst(str_replace('_', ' ', $task->current_status))) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $task->getPriorityBadgeClass() }}">
                                    {{ __(ucfirst($task->priority)) }} {{ __('Priority') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <p>Created by: {{ $task->reporter->name }}</p>
                            <p>{{ $task->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Task Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 uppercase">Assignee</h3>
                            <div class="mt-2 flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-2">
                                    {{ substr($task->assignee->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $task->assignee->name }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 uppercase">Start Date</h3>
                            <p class="mt-2 font-medium text-gray-900">{{ $task->start_date->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 uppercase">Due Date</h3>
                            <p class="mt-2 font-medium {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $task->end_date->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                        <div class="prose max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg">
                            {!! nl2br(e($task->description)) !!}
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Attachments</h3>
                        @if($task->attachments->count() > 0)
                            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($task->attachments as $attachment)
                                    <li class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between shadow-sm hover:shadow-md transition">
                                        <div class="flex items-center overflow-hidden">
                                            <svg class="h-8 w-8 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            <div class="truncate">
                                                <p class="text-sm font-medium text-gray-900 truncate" title="{{ $attachment->original_filename }}">
                                                    {{ $attachment->original_filename }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $attachment->formatSize() }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $attachment->getDownloadUrl() }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 p-2">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No attachments.</p>
                        @endif
                    </div>

                    <!-- Activity Log (Placeholder for now) -->
                    <!-- 
                    <div class="border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Activity History</h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($task->activityLogs as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">{{ $log->action }} by <span class="font-medium text-gray-900">{{ $log->actor->name }}</span></p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
