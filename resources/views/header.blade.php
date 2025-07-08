<header class="bg-white z-50">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="/" class="-m-1.5 p-1.5">

                <span class = "text-md font-bold" style="color: #2832c2">DIGITAL BLOG</span>
            </a>
        </div>
        <div class="flex lg:hidden">
            <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                <span class="sr-only">Open main menu</span>
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true" data-slot="icon">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            <a href="/" class="text-sm/6 font-semibold text-gray-600 hover:text-black">Feed</a>
            <a href="#" class="text-sm/6 font-semibold text-gray-600 hover:text-black">Community</a>
            <a href="#" class="text-sm/6 font-semibold text-gray-600 hover:text-black">Writting</a>
            <a href="#" class="text-sm/6 font-semibold text-gray-600 hover:text-black">About Us</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">

            @php
                $user = Auth::user();
            @endphp
            <a id="login-link" style="display:none" href="/page_login" class="text-sm/6 font-semibold text-gray-600 hover:text-black">Log in<span aria-hidden="true">&rarr;</span></a>
            <a id="account-link" href="/page_account" type="button" class="text-sm/6 font-semibold text-gray-600 hover:text-black" style="display:none">{{$user->name}}</a>
        </div>
    </nav>
    <!-- Mobile menu, show/hide based on menu open state. -->
    <div class="lg:hidden" role="dialog" aria-modal="true">
        <!-- Background backdrop, show/hide based on slide-over state. -->
        <div class="fixed inset-0 z-50"></div>
        <div
            class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
            <div class="flex items-center justify-between">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <img class="h-8 w-auto"
                        src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
                        alt="" />
                </a>
                <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Close menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="space-y-2 py-6">
                        <a href="/"
                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-400 hover:text-black hover:bg-gray-50">Feed</a>
                        <a href="#"
                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-400 hover:text-black hover:bg-gray-50">Community</a>
                        <a href="#"
                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-400 hover:text-black hover:bg-gray-50">Writting</a>
                        <a href="#"
                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-400 hover:text-black hover:bg-gray-50">About
                            Us</a>
                    </div>
                    <div class="py-6">
                        <button id="logout-btn-mobile"
                            class="-mx-3 block w-full text-left rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-600 hover:text-black hover:bg-gray-50"
                            style="display:none;">
                            Log out
                        </button>
                        <a id="login-btn-mobile" href="/page_login"
                            class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-600 hover:text-black hover:bg-gray-50"
                            style="display:none;">
                            Log in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        if(localStorage.getItem('token') === null){
            document.getElementById('login-link').style.display = 'block';
            document.getElementById('account-link').style.display = 'none';
        }else{
            document.getElementById('login-link').style.display = 'none';
            document.getElementById('account-link').style.display = 'block';
        }
    });
</script>
