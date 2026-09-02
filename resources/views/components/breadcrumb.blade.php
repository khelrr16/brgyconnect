@props(['items' => []])

<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 text-sm text-gray-500">

        {{-- Home --}}
        <li class="flex items-center gap-2">
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center gap-1.5 font-medium
                       text-gray-500 hover:text-indigo-600"
            >
                <i class="fa-solid fa-house text-xs"></i>
                Home
            </a>
        </li>

        @foreach($items as $item)

            {{-- Separator --}}
            <li class="text-gray-300">
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </li>

            {{-- Breadcrumb Item --}}
            <li>
                @if(!empty($item['url']))
                    <a
                        href="{{ $item['url'] }}"
                        class="inline-flex items-center gap-1.5
                               font-medium text-gray-500
                               hover:text-indigo-600"
                    >
                        @if(!empty($item['icon']))
                            <i class="{{ $item['icon'] }} text-xs"></i>
                        @endif

                        {{ $item['label'] }}
                    </a>
                @else
                    <span
                        class="inline-flex items-center gap-1.5
                               font-semibold text-gray-800"
                    >
                        @if(!empty($item['icon']))
                            <i class="{{ $item['icon'] }} text-xs text-indigo-600"></i>
                        @endif

                        {{ $item['label'] }}
                    </span>
                @endif
            </li>

        @endforeach

    </ol>
</nav>