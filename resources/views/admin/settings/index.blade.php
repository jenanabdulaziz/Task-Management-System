<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- General Settings -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('General Settings') }}</h3>
                            </div>

                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700">{{ __('Application Name') }}</label>
                                <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] ?? config('app.name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="items_per_page" class="block text-sm font-medium text-gray-700">{{ __('Items Per Page') }}</label>
                                <input type="number" name="items_per_page" id="items_per_page" value="{{ $settings['items_per_page'] ?? 10 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <!-- Notification Settings -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Notification Settings') }}</h3>
                            </div>

                            <div>
                                <label for="reminder_hours_before" class="block text-sm font-medium text-gray-700">{{ __('Send Reminder (Hours Before Due)') }}</label>
                                <input type="number" name="reminder_hours_before" id="reminder_hours_before" value="{{ $settings['reminder_hours_before'] ?? 24 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="enable_email_notifications" class="flex items-center mt-6">
                                    <input type="hidden" name="enable_email_notifications" value="0">
                                    <input type="checkbox" name="enable_email_notifications" id="enable_email_notifications" value="1" {{ ($settings['enable_email_notifications'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ms-2 text-sm text-gray-700">{{ __('Enable Email Notifications') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">{{ __('Save Settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
