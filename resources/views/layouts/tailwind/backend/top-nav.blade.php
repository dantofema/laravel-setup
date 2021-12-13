<div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
    <button class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
        <span class="sr-only">Open sidebar</span>
        <!-- Heroicon name: outline/menu-alt-2 -->
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h7"/>
        </svg>
    </button>
    <div class="flex-1 px-4 flex justify-between">
        <div class="flex-1 flex">
            <!-- top-nav search -->
        </div>
        <div class="ml-4 flex items-center md:ml-6">


            {{--            <x-setup.tailwind.top-nav.notifications--}}
            {{--                    :notificationsColor="$notificationsColor"--}}
            {{--                    :unreadNotifications="$unreadNotifications"--}}
            {{--            />--}}

            <x-setup.tailwind.top-nav.profile/>

        </div>
    </div>
</div>