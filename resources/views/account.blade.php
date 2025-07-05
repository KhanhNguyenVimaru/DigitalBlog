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

    <div class="flex justify-center items-start min-h-[calc(100vh-64px)] p-0">
        <div class="bg-white w-1/2 rounded-2xl shadow-2xl border border-gray-200 p-8 mt-4 mb-10">
            <div class="space-y-6">

                <!-- Title -->
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-600 md:text-2xl text-center">
                    Account Settings
                </h1>

                <!-- Full name -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-600">Full name</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2">
                        {{ Auth::user()->name }}
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-600">Email address</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <!-- Privacy + Description -->
                <form class="space-y-4">
                    <!-- Privacy -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">Privacy</label>
                        <select class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">Description</label>
                        <textarea class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full resize-none" rows="4" placeholder="Description"></textarea>
                    </div>

                    <!-- Save settings button -->
                    <div class="flex justify-end">
                        <button type="submit" class="w-1/3 mt-[10px] text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer">
                            Save settings
                        </button>
                    </div>
                </form>
                <!-- Change password -->
                <form class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">New password</label>
                        <input type="password" class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full" placeholder="New password" />
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">Confirm new password</label>
                        <input type="password" class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full" placeholder="Confirm new password" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="mt-[10px] text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-1/3 cursor-pointer">
                            Change password
                        </button>
                    </div>
                </form>

                <!-- Logout -->
                <form id="logout-form-account" action="/logout" method="POST">
                    @csrf
                    <div class="flex justify-end">
                        <button type="submit" class="mt-5 flex items-center justify-center gap-2 px-5 py-2.5 bg-transparent text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg font-medium text-sm focus:outline-none focus:ring-2 focus:ring-red-100 w-full transition-colors cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-6-3h12m0 0l-3-3m3 3l-3 3" />
                            </svg>
                            Log out
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden logout form -->
    <form id="logout-form" action="/logout" method="POST" style="display: none;">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('logout-link')?.addEventListener('click', function (e) {
            e.preventDefault();
            const token = localStorage.getItem('token');
            fetch('/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    localStorage.clear();
                    window.location.href = '/page_login';
                }
            });
        });
    </script>
</body>

</html>
