@php use Illuminate\Support\Str; @endphp
@vite(['resources/css/app.css', 'resources/js/app.js'])
@include('header')

<div class="container mx-auto py-8 flex justify-center bg-gray-100" style="min-height:90vh">
    <div class="w-3/5 bg-white rounded-lg p-8 shadow-md">
        <h5 class="text-lg font-bold mb-4 text-center">Search results for: "{{ $query }}"</h5>

        <div class="flex justify-center gap-2 mb-8 w-full max-w-md mx-auto">
            <button class="filter-btn w-1/3 px-2 py-1 rounded bg-blue-500 text-white text-sm focus:outline-none" data-target="posts">Posts</button>
            <button class="filter-btn w-1/3 px-2 py-1 rounded bg-blue-500 text-white text-sm focus:outline-none" data-target="users">Users</button>
            <button class="filter-btn w-1/3 px-2 py-1 rounded bg-blue-500 text-white text-sm focus:outline-none" data-target="categories">Categories</button>
        </div>

        <div id="posts" class="filter-section mb-8">
            <h3 class="text-base font-semibold mb-2">Posts</h3>
            @forelse($posts as $post)
                @php
                    $img = $post->additionFile ? asset('storage/uploads/' . $post->additionFile) : asset('images/free-images-for-blog.png');
                    $contentArr = json_decode($post->content, true);
                    $preview = '';
                    if (isset($contentArr['blocks'])) {
                        foreach ($contentArr['blocks'] as $block) {
                            if (isset($block['data']['text'])) {
                                $preview .= strip_tags($block['data']['text']) . ' ';
                            }
                        }
                        $preview = Str::limit($preview, 100);
                    }
                    $categoryName = $post->category->content ?? 'No category';
                    $status = ucfirst($post->status);
                    $createdAt = \Carbon\Carbon::parse($post->created_at)->format('d/m/Y H:i');
                @endphp
                <div class="bg-white rounded-lg shadow p-4 flex flex-row gap-4 items-center mb-4 hover:shadow-lg transition min-h-[120px]">
                    <img src="{{ $img }}" alt="Post Image" class="w-20 h-20 object-cover rounded-md bg-gray-100" onerror="this.src='{{ asset('images/free-images-for-blog.png') }}'">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('post.content.viewer', ['id' => $post->id]) }}" class="font-bold text-base text-black cursor-pointer post-title-hover block truncate hover:text-blue-600 hover:underline-0" style="text-decoration: none;">{{ $post->title }}</a>
                        <div class="text-gray-600 text-sm mt-1 truncate">{{ $preview }}</div>
                        <div class="flex flex-row items-center gap-2 mt-2">
                            <span class="inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold cursor-pointer">{{ $categoryName }}</span>
                            <span class="px-2 py-1 rounded text-xs {{ $post->status === 'public' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} cursor-pointer">{{ $status }}</span>
                            <span class="text-xs text-gray-400 ml-auto">{{ $createdAt }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-gray-500">No matching posts found.</div>
            @endforelse
        </div>

        <div id="users" class="filter-section mb-8 hidden">
            <h3 class="text-base font-semibold mb-2">Users</h3>
            @forelse($users as $user)
                @php
                    $avatar = $user->avatar ? asset($user->avatar) : 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg';
                @endphp
                <div class="flex items-center gap-4 mb-4 bg-white rounded-lg shadow p-4 hover:bg-gray-100 transition">
                    <img src="{{ $avatar }}" alt="Avatar" class="w-12 h-12 object-cover rounded-full bg-white" onerror="this.src='https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg'">
                    <div class="flex flex-col">
                        <a href="/user-profile/{{ $user->id }}" class="font-bold text-base text-black cursor-pointer user-title-hover hover:text-blue-600 hover:underline-0" style="text-decoration: none;">{{ $user->name }}</a>
                        <span class="text-xs text-gray-500 mt-1">{{ $user->email }}</span>
                    </div>
                </div>
            @empty
                <div class="text-gray-500">No matching users found.</div>
            @endforelse
        </div>

        <div id="categories" class="filter-section mb-8 hidden">
            <h3 class="text-base font-semibold mb-2">Categories</h3>
            @forelse($categories as $cat)
                <div class="p-3 border-b">
                    <span class="text-gray-700">{{ $cat->content }}</span>
                </div>
            @empty
                <div class="text-gray-500">No matching categories found.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btns = document.querySelectorAll('.filter-btn');
        const sections = document.querySelectorAll('.filter-section');
        btns.forEach(btn => {
            btn.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                sections.forEach(sec => {
                    if (sec.id === target) {
                        sec.classList.remove('hidden');
                    } else {
                        sec.classList.add('hidden');
                    }
                });
                btns.forEach(b => b.classList.remove('bg-blue-700'));
                this.classList.add('bg-blue-700');
            });
        });
        // Default: show posts
        btns[0].classList.add('bg-blue-700');
    });
</script>

@include('footer') 