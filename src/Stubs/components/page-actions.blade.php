@props([
    'action' => null,
    'id' => null,
])
<div>

    @if(empty($action))
        <x-jet-secondary-button wire:click="create" class="text-blue-500">
            <x-heroicon-s-plus class='inline-block w-6 h-6 pr-2'/>
            <span>Crear</span>
        </x-jet-secondary-button>
    @endif

    @if($action == 'create')
        <x-jet-secondary-button wire:click="index">
            <x-heroicon-s-view-list class='inline-block w-6 h-6 pr-2'/>
            <span>Listar</span>
        </x-jet-secondary-button>
    @endif

    @if($action == 'edit')
        <x-jet-secondary-button wire:click="index">
            <x-heroicon-s-view-list class='inline-block w-6 h-6 pr-2'/>
            <span>Listar</span>
        </x-jet-secondary-button>

        <x-jet-secondary-button wire:click="create" class="text-blue-500">
            <x-heroicon-s-plus class='inline-block w-6 h-6 pr-2'/>
            <span>Crear</span>
        </x-jet-secondary-button>

        <x-jet-secondary-button wire:click="delete({{ $id ?? null }})" class="text-red-500">
            <x-heroicon-s-trash class='inline-block w-6 h-6 pr-2'/>
            <span>Eliminar</span>
        </x-jet-secondary-button>
    @endif


</div>

