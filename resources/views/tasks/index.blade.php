<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap items-end gap-4">
                        <!-- Status Filter -->
                        <div class="flex-1 min-w-[150px]">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>{{ __('Not Started') }}</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>

                        <!-- Priority Filter -->
                        <div class="flex-1 min-w-[150px]">
                            <x-input-label for="priority" :value="__('Priority')" />
                            <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                <option value="">{{ __('All Priorities') }}</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>{{ __('Normal') }}</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="flex-1 min-w-[150px]">
                            <x-input-label for="sort" :value="__('Sort By')" />
                            <select id="sort" name="sort" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                <option value="due_date" {{ request('sort') == 'due_date' ? 'selected' : '' }}>{{ __('Due Date') }}</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('Created Date') }}</option>
                                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>{{ __('Priority') }}</option>
                            </select>
                        </div>
                        
                        <!-- Date From -->
                        <div class="flex-1 min-w-[150px]">
                            <x-input-label for="date_from" :value="__('Date From')" />
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                        </div>

                        <!-- Date To -->
                        <div class="flex-1 min-w-[150px]">
                            <x-input-label for="date_to" :value="__('Date To')" />
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                        </div>

                        <div class="flex items-end gap-3">
                            <x-primary-button type="submit" class="justify-center px-1 py-1 text-xs h-[36px]" style="width: 100px; height: 36px; margin-top: 25px;">
                                {{ __('Filter') }}
                            </x-primary-button>
                            <a href="{{ route('tasks.export', request()->query()) }}" class="inline-flex items-center px-1 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 h-[36px]" style="background-color: #16a34a; width: 100px; height: 36px; margin-top: 25px; margin-inline: 16px;">
                                {{ __('Export CSV') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">{{ __('My Tasks') }}</h3>
                        @if(auth()->user()->can('create', App\Models\Task::class))
                            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Create Task') }}
                            </a>
                        @endif
                    </div>

                    @if($tasks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Title') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Priority') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Due Date') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                        {{ $task->title }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->getStatusBadgeClass() }}">
                                                    {{ __($task->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->getPriorityBadgeClass() }}">
                                                    {{ __($task->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $task->end_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center">
                                                    <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" style="margin-inline-end: 15px;">{{ __('View') }}</a>
                                                    @can('update', $task)
                                                        <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">{{ __('Edit') }}</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('No tasks found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
