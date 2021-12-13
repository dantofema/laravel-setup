<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex flex-col h-0 flex-1">
            <div class="flex items-center h-16 flex-shrink-0 px-4 bg-gray-900">
                <img class="h-8 w-auto"
                     src="{{ asset('images/logo.png') }}"
                     alt="Workflow">
                <span class="text-gray-300 ml-5">UOM SERVICIOS</span>
            </div>
            <div class="flex-1 flex flex-col overflow-y-auto">
                <nav class="flex-1 px-2 py-4 bg-gray-800 space-y-1">

                    <x-setup.tailwind.sidebar.link href="{{ url('sistema/inicio') }}"
                                                   id="sidebar-item-dashboard">
                        <x-heroicon-o-home class="h-6 m-6 mr-2"/>
                        Inicio
                    </x-setup.tailwind.sidebar.link>

                    @can('archivos-externos')
                        <x-setup.tailwind.sidebar.link href="{{ url('sistema/archivos-externos') }}"
                                                       id="sidebar-item-external-files">
                            <x-heroicon-o-home class="mr-2"/>
                            Archivos externos
                        </x-setup.tailwind.sidebar.link>
                    @endcan


                </nav>
            </div>
        </div>
    </div>
</div>