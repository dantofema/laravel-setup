<a
        {{ $attributes }}
        {{--   class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">--}}
        class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <!--
      Current: "text-gray-300", Default: "text-gray-400 group-hover:text-gray-300"
    -->
    {{$slot}}
</a>
