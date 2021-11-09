<x-jet-dialog-modal wire:model.defer="showFormModal">

    <x-slot name="title">
        <span wire:model="titleFormModal">{{ $titleFormModal }}</span>
    </x-slot>
    <x-slot name="content">
        {{--        <div class="space-y-2">--}}
        {{--            <x-jet-label class="space-y-2">--}}
        {{--                <div class="w-1/2">Título</div>--}}
        {{--                <x-jet-input wire:model="editing.title" type="text" class="w-full"/>--}}
        {{--            </x-jet-label>--}}


        {{--            <x-jet-label class="space-y-2">--}}
        {{--                <div class="w-1/2">Texto</div>--}}
        {{--                <label>--}}
        {{--                    <textarea wire:model="editing.body" class="w-full" rows="15"></textarea>--}}
        {{--                </label>--}}


        {{--                <x-jet-label class="space-y-2">--}}
        {{--                    <div class="w-1/2">Categoría</div>--}}
        {{--                    <label>--}}
        {{--                        <select wire:model="editing.category_id">--}}

        {{--                            <option value="">Seleccione...</option>--}}
        {{--                            @foreach($categories as $row)--}}
        {{--                                <option value="{{ $row->id }}">{{ $row->name }}</option>--}}
        {{--                            @endforeach--}}

        {{--                        </select>--}}
        {{--                    </label>--}}
        {{--                </x-jet-label>--}}
        {{--            </div>--}}

        {{--            <div class="pt-5">--}}

        {{--                <x-jet-label class="space-y-2">--}}
        {{--                    <div class="w-1/2">Foto</div>--}}
        {{--                    <div>--}}
        {{--                        <img src="{{ $editing->src }}"--}}
        {{--                             alt=""--}}
        {{--                             class="h-48">--}}
        {{--                    </div>--}}
        {{--                    <x-jet-input wire:model="newImage" type="file" class="w-full"/>--}}
        {{--                </x-jet-label>--}}

        {{--            </div>--}}
        {{--        </div>--}}


        <div>
            <x-jet-validation-errors/>
        </div>

    </x-slot>

    <x-slot name="footer">
        <x-jet-button wire:click="$set('showFormModal', false)">Cancelar</x-jet-button>
        <x-jet-button wire:click="save" class="bg-blue-700">Confirmar</x-jet-button>
    </x-slot>

</x-jet-dialog-modal>