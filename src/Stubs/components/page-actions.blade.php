@props([
    'action' => null,
    'id' => null,
])
<div>

    @if(empty($action))
        <x-jet-secondary-button wire:click="create">
            <x-heroicon-o-plus class='inline-block w-5 h-5'/>
            <span>Crear</span>
        </x-jet-secondary-button>
    @endif

    @if($action == 'create')
        <x-jet-secondary-button wire:click="index">
            <x-heroicon-o-plus class='inline-block w-5 h-5'/>
            <span>Listar</span>
        </x-jet-secondary-button>
    @endif

    @if($action == 'edit')
        <x-jet-secondary-button wire:click="index">
            <x-heroicon-o-plus class='inline-block w-5 h-5'/>
            <span>Listar</span>
        </x-jet-secondary-button>

        <x-jet-secondary-button wire:click="create">
            <x-heroicon-o-plus class='inline-block w-5 h-5'/>
            <span>Crear</span>
        </x-jet-secondary-button>

        <x-jet-secondary-button wire:click="delete({{ $id ?? null }})">
            <x-heroicon-o-plus class='inline-block w-5 h-5'/>
            <span>Eliminar</span>
        </x-jet-secondary-button>
    @endif


</div>

