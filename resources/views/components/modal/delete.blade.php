@props([
     'message' => ''
])

<x-jet-dialog-modal wire:model.defer="showDeleteModal">
    <x-slot name="title">Eliminar</x-slot>
    <x-slot name="content">
        <p class="mb-3">{{ $message }}</p>
    </x-slot>
    <x-slot name="footer">
        <x-jet-button wire:click="$set('showDeleteModal', false)">Cancelar</x-jet-button>
        <x-jet-button wire:click="confirmDelete" class="bg-blue-700">Confirmar</x-jet-button>
    </x-slot>
</x-jet-dialog-modal>