@props([
    'src' => null,
])
<x-table.cell>
    <div class="flex inline-flex space-x-2">
        <img src="{{ $src }}" class="h-12" alt="">
        <div>
            {{ $slot }}
        </div>
    </div>
</x-table.cell>