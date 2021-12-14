@props([
    'disabled' => false,
    'initialValue' => ''
    ])

<div
        wire:ignore
        x-data
        @trix-blur="$dispatch('change', $event.target.value)"
        {{ $attributes }}
>

    <input
            id="x"
            type="hidden"
            value="{{ $initialValue }}"
    >

    <trix-editor
            input="x"
            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            {{ $disabled ? 'disabled' : '' }}
    ></trix-editor>
</div>
