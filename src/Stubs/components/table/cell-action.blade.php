@props([
    'delete' => null,
    'edit' => null,
    'row'=>null,
])
<x-table.cell>

    <div class="flex inline-flex space-x-2">

        @if($edit)
            <x-jet-secondary-button wire:click="edit({{ $row->id }})">
                <x-heroicon-o-archive class="inline-block h-6 w-6 text-green-300"/>
            </x-jet-secondary-button>
        @endif

        @if($delete)
            <x-jet-secondary-button wire:click="delete({{ $row->id }})">
                <x-heroicon-s-trash class="inline-block h-6 w-6 text-red-300"/>
            </x-jet-secondary-button>
        @endif

    </div>
</x-table.cell>
