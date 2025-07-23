<div class="max-w-7xl mx-auto pl-6 pt-2 pb-2">
    <nav class="text-sm m-0" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            @foreach ($links as $i => $link)
                <li class="flex items-center">
                    <a href="{{ $link['url'] ?? request()->url() }}"
                       class="px-2 py-1 rounded transition font-medium
                              {{ $loop->last ? 'bg-blue-100 text-blue-700 font-bold hover:bg-blue-200' : 'bg-blue-50 text-blue-500 hover:bg-blue-200 hover:text-blue-700' }}"
                       style="text-decoration: none;">
                        {{ $link['label'] }}
                    </a>
                    @if (!$loop->last)
                        <svg class="w-4 h-4 mx-1 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>