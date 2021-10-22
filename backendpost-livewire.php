<div>
    <x-header title="Notas"/>

    <x-table class="mt-10" :rows="$rows" colspan="3">

        <x-slot name="head">
            <x-table.heading select/>

            <x-table.heading
                    sortable
                    :direction="$sorts['name'] ?? null"
                    sortBy="name"
                    class="w-full"
            >
                Nombre
            </x-table.heading>
            <x-table.heading/>
        </x-slot>

        <x-slot name="body">

            @foreach($rows as $row)
                <x-table.row id="{{ $row->id }}">
                    <x-table.cell select id="{{ $row->id }}"/>
                    <x-table.cell class="space-y-2">
                        <div>{{ $row->name }}</div>
                        <div>
                            @foreach($row->subcategories as  $subcategory)
                                <x-badge.simple color="indigo">{{ $subcategory->name }}</x-badge.simple>
                            @endforeach
                        </div>
                    </x-table.cell>
                    <x-table.cell actions edit show delete id="{{ $row->id }}"/>
                </x-table.row>
            @endforeach

        </x-slot>

    </x-table>

    @if($showEditModal)
        <x-modal.dialog wire:model.defer="showEditModal">

            <x-slot name="title">Editar</x-slot>

            <x-slot name="content">
                <x-input.group label="Nombre" for="editing.name">
                    <x-input.text wire:model.defer="editing.name" id="editing.name"/>
                </x-input.group>
            </x-slot>

        </x-modal.dialog>
    @endif

    @if($showCreateModal)
        <x-modal.dialog wire:model.defer="showCreateModal">

            <x-slot name="title">Nueva</x-slot>

            <x-slot name="content">
                <x-input.group label="Nombre" for="editing.name">
                    <x-input.text wire:model.defer="editing.name" id="editing.name"/>
                </x-input.group>
            </x-slot>

        </x-modal.dialog>
    @endif


</div>

