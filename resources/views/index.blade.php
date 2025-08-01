<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digital Blog</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('header')
    <div id="animation-carousel" class="relative w-full" data-carousel="slide" data-carousel-interval="10000">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden md:h-96">
            <!-- Item 1 -->
            <div class="hidden transition-all duration-1000 ease-in-out" data-carousel-item>
                <img src="{{ asset('images/carousel_img/image_1.png') }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 2 -->
            <div class="hidden transition-all duration-1000 ease-in-out" data-carousel-item="active">
                <img src="{{ asset('images/carousel_img/image_2.png') }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
        </div>

        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <svg class="w-6 h-6 text-white group-hover:text-gray-300 transition-colors" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 1 1 5l4 4" />
            </svg>
            <span class="sr-only">Previous</span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <svg class="w-6 h-6 text-white group-hover:text-gray-300 transition-colors" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
            </svg>
            <span class="sr-only">Next</span>
        </button>
    </div>

    <!-- Post Section -->
    <div class="w-full mt-4 min-h-[60vh]">
        <div class="max-w-screen-xl mx-auto">
            <div class="my-9 flex flex-row items-center">
                <h4 class="ml-4 md:ml-6 font-bold text-xl">TOP BEST</h4>
                <a href="#" class="text-gray-500 ml-6 text-base">Top Highlighted Posts</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4" id="top-posts">
                @foreach ($topLikedPosts as $post)
                    <div class="bg-white p-4 rounded shadow">
                        <a href="{{ url('/post-content-viewer/' . $post->id) }}">
                            <img src="{{ $post->additionFile ?? '/images/free-images-for-blog.png' }}"
                                onerror="this.src='/images/free-images-for-blog.png'" alt="Post Image"
                                class="w-full h-36 object-cover rounded-md bg-gray-100" />
                            <span class="font-bold mt-2 line-clamp-2 h-[56px]">
                                {{ $post->title ?? 'Untitled Post' }}
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('footer')

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>

</html>
