<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">
                        {{ __('Mark all as read') }}
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($notifications->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($notifications as $notification)
                                <li class="py-4 {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50 -mx-6 px-6' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $notification->data['message'] ?? __('New Notification') }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                            @if(isset($notification->data['link']))
                                                <a href="{{ $notification->data['link'] }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">
                                                    {{ __('View Details') }}
                                                </a>
                                            @endif
                                        </div>
                                        @if(!$notification->read_at)
                                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                                @csrf
                                                <button type="submit" class="ml-4 text-xs text-gray-400 hover:text-gray-600">
                                                    {{ __('Mark as read') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">{{ __('No notifications') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
