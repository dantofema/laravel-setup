<div
        x-data
        x-init="
            new Pikaday({field: $refs.input, format: 'DD-MM-YYYY' })
        "
>
    <div class="mt-1 relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <x-heroicon-s-calendar class="h-5 w-5 text-gray-400"/>
        </div>
        <x-jet-input
                {{ $attributes }}
                x-ref="input"
                type="text"/>
    </div>


</div>