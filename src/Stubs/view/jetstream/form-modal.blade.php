<x-jet-dialog-modal wire:model.defer="showFormModal">

    <x-slot name="title">
        <span wire:model="titleFormModal">{{ $titleFormModal }}</span>
    </x-slot>
    <x-slot name="content">

        :fields:

        <div>
            <x-jet-validation-errors/>
        </div>

    </x-slot>

    <x-slot name="footer">
        <x-jet-button wire:click="$set('showFormModal', false)">Cancelar</x-jet-button>
        <x-jet-button wire:click="save" class="bg-blue-700">Confirmar</x-jet-button>
    </x-slot>

</x-jet-dialog-modal>