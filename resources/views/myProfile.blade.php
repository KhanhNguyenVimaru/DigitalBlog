@php
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Account - Blog</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">
    @include('header')
    <div class="max-w-2xl mx-auto mt-4 p-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col items-center">
            <img src="{{ $user->avatar ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 mb-4" alt="Avatar">
            <h2 class="text-2xl font-bold mb-1">{{ $user->name }}</h2>
            <span class="text-gray-500 mb-2">{{ $user->email }}</span>
            @if ($user->privacy === 'public')
                <span
                    class="px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700 mb-2">{{ $user->privacy }}</span>
            @else
                <span class="px-3 py-1 rounded-full text-xs bg-gray-200 text-gray-700 mb-2">{{ $user->privacy }}</span>
            @endif
        </div>
    </div>

    <div class="max-w-2xl mx-auto mt-4 p-5 bg-white rounded-lg shadow-md">
        @if ($user->description)
            <div class="text-gray-600  mt-0 mb-8 px-40">{{ $user->description }}</div>
        @endif
        <div class="flex justify-center gap-8">
            <a href="#" id="following-link" class="text-center">
                <div class="text-lg font-bold text-gray-800">{{ $user->following_count ?? 0 }}</div>
                <div class="text-xs text-gray-500 uppercase tracking-wide">Following</div>
            </a>  
            <a href="#" id="followers-link" class="text-center">
                <div class="text-lg font-bold text-gray-800">{{ $user->followers_count ?? 0 }}</div>
                <div class="text-xs text-gray-500 uppercase tracking-wide">Followers</div>
            </a>
        </div>
    </div>

    <!-- Modal Followers -->
    <div id="modal-followers"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/75 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0 w-full">
            <div id="modal-followers-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-base font-semibold text-gray-900">Followers</h3>
                    <!-- Nội dung modal followers -->
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button"
                        class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 cursor-pointer"
                        onclick="closeModal('modal-followers')">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Following -->
    <div id="modal-following"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/75 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0 w-full">
            <div id="modal-following-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-base font-semibold text-gray-900">Following</h3>
                    <!-- Nội dung modal following -->
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button"
                        class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 cursor-pointer"
                        onclick="closeModal('modal-following')">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Privacy -->
    <div id="modal-privacy"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/75 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0 w-full">
            <div id="modal-privacy-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Privacy</h3>
                    <!-- Nội dung modal privacy -->
                    <div class="text-gray-700 text-sm leading-relaxed">
                        <p class="mb-4 text-left">
                            Users who set their account privacy to <span
                                class="font-semibold text-gray-900">Private</span> must approve each follow request
                            manually, meaning other users need to send a follow request and wait for approval.<br>
                            In contrast, users with <span class="font-semibold text-gray-900">Public</span> accounts can
                            be followed instantly without requiring any approval.
                        </p>
                        <span class="text-left block">Want to reset your privacy? <a href="/page_account"
                                class="text-indigo-600 font-semibold hover:underline transition">Setting here</a></span>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button"
                        class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 cursor-pointer"
                        onclick="closeModal('modal-privacy')">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            const panel = document.getElementById(id + '-panel');
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.classList.add('opacity-100');
            setTimeout(() => {
                panel.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
                panel.classList.add('opacity-100', 'scale-100', 'translate-y-0');
            }, 10);
            // Đóng modal khi click ra ngoài panel
            modal.addEventListener('mousedown', function handler(e) {
                if (!panel.contains(e.target)) {
                    closeModal(id);
                    modal.removeEventListener('mousedown', handler);
                }
            });
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const panel = document.getElementById(id + '-panel');
            panel.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
            panel.classList.add('opacity-0', 'scale-95', 'translate-y-4');
            setTimeout(() => {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
            }, 200);
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('followers-link').addEventListener('click', function(e) {
                e.preventDefault();
                openModal('modal-followers');
            });
            document.getElementById('following-link').addEventListener('click', function(e) {
                e.preventDefault();
                openModal('modal-following');
            });
            var privacyBadge = document.querySelector('.bg-indigo-100.text-indigo-700, .bg-gray-200.text-gray-700');
            if (privacyBadge) {
                privacyBadge.style.cursor = 'pointer';
                privacyBadge.addEventListener('click', function(e) {
                    openModal('modal-privacy');
                });
            }
        });
    </script>
</body>

</html>
