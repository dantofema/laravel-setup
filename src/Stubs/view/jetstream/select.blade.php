<x-jet-label class="space-y-2">

    <label>
        <div class="w-1/2">:label:</div>

        <select wire:model="editing.:field:">

            <option value="">Seleccione...</option>
            @foreach($:arrayItems: as $:modelLower:)
                <option value="{{ $:modelLower:->id }}">{{ $:modelLower:->:optionField: }}</option>
            @endforeach

        </select>
    </label>

</x-jet-label>