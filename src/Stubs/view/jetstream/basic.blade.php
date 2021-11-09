<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


        <div class="flex justify-between space-x-2">

            <x-input.search/>

            <div class="flex justify-end space-x-2">
                <x-button.create text="Nueva nota"/>
            </div>

        </div>

        <x-table>
            <x-slot name="head">
                :table-headings:
                <x-table.heading/>
            </x-slot>
            <x-slot name="body">
                @foreach($rows as $row)
                    <x-table.row>
                        :table-cells:
                        <x-table.cell-action :row="$row" edit delete/>
                    </x-table.row>
                @endforeach
            </x-slot>

        </x-table>

        <x-table.links :rows="$rows"/>

        :deleteModal:

        :formModal:

    </div>
</div>