<!-- Profile dropdown -->
<div x-data="{ show: false }"
     class="ml-3 relative" id="profile-dropdown">
    <div>
        <button @click="show = !show"
                type="button"
                class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                id="profile-dropdown-button"
                aria-expanded="false"
                aria-haspopup="true">
            <span class="sr-only">Open user menu</span>
            <x-heroicon-o-user class="h-6 w-6 text-gray-400"/>
        </button>
    </div>

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
         aria-labelledby="profile-dropdown-button"
         tabindex="-1"
         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none divide-y"
    >

        <div
                id="profile-dropdown-info-profile"
                class="flex flex-col px-4 py-2 text-xs text-gray-600 space-y-2"
        >
            <span id="profile-dropdown-info-profile-name">
                {{ auth()->user()->name }}
            </span>
        </div>

        <div>
            <a href="{{ url('/sistema/perfil') }}"
               class="block px-4 py-2 text-sm text-gray-700"
               role="menuitem" tabindex="-1"
               id="profile-dropdown-item-profile"
            >
                Tu perfil
            </a>

            <a href="{{ url('/logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit()" ;
               class="block px-4 py-2 text-sm text-gray-700"
               role="menuitem"
               tabindex="-1"
               id="profile-dropdown-item-logout">
                Cerrar sesión
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </div>

    </div>
</div>