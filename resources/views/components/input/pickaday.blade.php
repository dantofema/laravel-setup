<div>
    <div class="mt-1 relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <x-heroicon-s-calendar class="h-5 w-5 text-gray-400"/>
        </div>
        <x-jet-input
                {{ $attributes }}
                type="text"/>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    @endpush

    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    @endpush


</div>