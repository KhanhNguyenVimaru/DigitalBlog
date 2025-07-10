@php
    $user = Auth::user();
    $role = $user ? $user->role : null;
@endphp
<header class="bg-white z-50">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex flex-1 min-w-0">
            <a href="/" class="flex items-center gap-2 -m-1.5 p-1.5">
                <span class="text-md font-bold" style="color: #2832c2">DIGITAL BLOG</span>
            </a>
        </div>
        <div class="flex flex-wrap gap-4 flex-1 justify-center min-w-0">
            <a href="/" class="text-sm font-semibold text-gray-600 hover:text-black px-2">Feed</a>
            <a href="#" class="text-sm font-semibold text-gray-600 hover:text-black px-2">Community</a>
            <a href="/myProfile" class="text-sm font-semibold text-gray-600 hover:text-black px-2">Following</a>
            <a href="/writing" class="text-sm font-semibold text-gray-600 hover:text-black px-2">Writing</a>
            @if ($role === 'admin') 
                <a href="/admin" class="text-sm f   ont-semibold text-gray-600 hover:text-black px-2">Admin</a>
            @endif
        </div>
        <div class="flex flex-1 justify-end min-w-0">
            <a id="login-link" style="display:none" href="/page_login"
                class="text-sm font-semibold text-gray-600 hover:text-black">Log in<span
                    aria-hidden="true">&rarr;</span></a>
            <div id="account-dropdown-wrapper" class="relative" style="display:none;">
                <button id="account-link" type="button"
                    class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-black focus:outline-none cursor-pointer">
                    <span>{{ $user->name }}</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="account-dropdown"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50">
                    <a href="/my-profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                    <a href="/page_account" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Setting</a>
                    <button id="logout-btn" type="button"
                        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 text-red-600 cursor-pointer">Logout</button>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="/js/logout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginLink = document.getElementById('login-link');
        const accountWrapper = document.getElementById('account-dropdown-wrapper');
        const accountLink = document.getElementById('account-link');
        const accountDropdown = document.getElementById('account-dropdown');
        const mobileWrapper = document.getElementById('mobile-account-wrapper');

        // Hiển thị đúng trạng thái đăng nhập
        if (loginLink && accountWrapper) {
            if (localStorage.getItem('token') === null) {
                loginLink.style.display = 'block';
                accountWrapper.style.display = 'none';
                if (mobileWrapper) mobileWrapper.style.display = 'none';
            } else {
                loginLink.style.display = 'none';
                accountWrapper.style.display = 'block';
                if (mobileWrapper) mobileWrapper.style.display = 'block';
            }
        }

        // Dropdown logic
        if (accountLink && accountDropdown) {
            // Đóng dropdown khi click ra ngoài
            document.addEventListener('click', function (e) {
                if (!accountDropdown.contains(e.target) && !accountLink.contains(e.target)) {
                    accountDropdown.classList.add('hidden');
                }
            });

            // Toggle dropdown khi click vào account
            accountLink.addEventListener('click', function (e) {
                e.preventDefault();
                accountDropdown.classList.toggle('block');
            });
        }
    });
</script>
