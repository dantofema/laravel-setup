<!-- notifications dropdown -->
<div x-data="{ show: false }"
     class="ml-3 relative" id="notifications-dropdown"
>
    <button @click="show = !show"
            type="button"
            class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            id="notifications-dropdown-button"
            aria-expanded="false"
            aria-haspopup="true"
            wire:poll.5s="unreadNotifications"
    >
        <span class="sr-only">Open user menu</span>
        <x-heroicon-o-bell class="h-6 w-6 text-{{ $notificationsColor }}-400"/>
    </button>


    <div x-show="show"
         @click.outside="show = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="notifications-dropdown-button"
         tabindex="-1"
         class="origin-top-right absolute right-0 mt-2 w-96 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none divide-y"
    >

        <div
                id="notifications-dropdown-info-notifications"
                class="flex flex-col px-4 py-2 text-xs text-gray-600 space-y-2"
        >

        @forelse($unreadNotifications as $notification)

            <!-- This example requires Tailwind CSS v2.0+ -->
                <div wire:click="markAsRead('{{ $notification->id }}')"
                     class="bg-{{ $notification->data['color'] }}-50 border-l-4 border-{{ $notification->data['color'] }}-400 p-4 cursor-pointer"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: solid/exclamation -->
                            <svg class="h-5 w-5 text-{{ $notification->data['color'] }}-400"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="ml-3 text-{{ $notification->data['color'] }}-700 w-full">
                            <div class="flex justify-between ">
                                <div class="font-lg">
                                    {{ $notification->data['title'] }}
                                </div>

                                <div class="text-xs">
                                    {{ $notification->created_at }}
                                </div>

                            </div>

                            <div class="font-medium w-full">
                                {{ $notification->data['message'] }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                        class="bg-gray-50 border-l-4 border-gray-400 p-4 cursor-pointer"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: solid/exclamation -->
                            <svg class="h-5 w-5 text-gray-400"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-gray-700 text-xs">
                                {{ now() }}
                            </p>
                            <p class="font-medium text-gray-700 mt-3">
                                Sin notificaciones por leer.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>