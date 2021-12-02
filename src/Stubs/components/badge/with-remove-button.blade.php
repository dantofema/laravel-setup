@props([
    'color'=>'blue',
    'text'=>'',
    'id'=>null,
    'method'=>null,
])
<span class="inline-flex items-center py-0.5 pl-2 pr-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-700">
  {{ $text }}

  <button
          wire:click="{{ $method }}({{ $id }})"
          type="button"
          class="flex-shrink-0 ml-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-{{ $color }}-400 hover:bg-{{ $color }}-200 hover:text-{{ $color }}-500 focus:outline-none focus:bg-{{ $color }}-500 focus:text-white">
    <span class="sr-only">Remove small option</span>
    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
      <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"/>
    </svg>
  </button>

</span>