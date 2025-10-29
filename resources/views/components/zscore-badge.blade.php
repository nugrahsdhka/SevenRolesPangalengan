@props(['value'])

<span
    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
    @if (is_null($value))
        bg-gradient-to-r from-gray-400 to-gray-500 text-white
    @elseif ($value < -3)
        bg-gradient-to-r from-red-500 to-red-600 text-white
    @elseif ($value < -2)
        bg-gradient-to-r from-orange-500 to-orange-600 text-white
    @elseif ($value <= 2)
        bg-gradient-to-r from-green-500 to-green-600 text-white
    @else
        bg-gradient-to-r from-blue-500 to-blue-600 text-white
    @endif"
>
    @if (is_null($value))
        N/A
    @else
        {{ number_format($value, 2) }}
    @endif
</span>
