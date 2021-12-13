@props(['disabled' => false])
<div
        wire:ignore
        x-data
        @trix-change="$dispatch('change', $event.value)"

>

    <input id="x" type="hidden" name="content">

    <trix-editor
            input="x"
            {{ $attributes }}
            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            {{ $disabled ? 'disabled' : '' }}
    ></trix-editor>

</div>
