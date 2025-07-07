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
                @php
                    $user = Auth::user();
                @endphp
                <form class="space-y-4" method="POST" action="{{ route('updateUserData', $user->id) }}">
                    @csrf
                    @method('PATCH')
                    <!-- Privacy -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">Privacy</label>
                        <select class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full"
                            name = "privacy" value = "{{ $user->privacy }}">
                            <option value="public" {{ old('privacy', $user->privacy) == 'public' ? 'selected' : '' }}>
                                Public</option>
                            <option value="private" {{ old('privacy', $user->privacy) == 'private' ? 'selected' : '' }}>
                                Private</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">Description</label>
                        <textarea class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full resize-none" rows="4"
                            placeholder="Description" name = "description">{{ $user->description }}</textarea>
                    </div>

                    <!-- Save settings button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="w-1/3 mt-[10px] text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer">
                            Save settings
                        </button>
                    </div>
                </form>

                <!-- Change password -->
                <form class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">New password</label>
                        <input type="password"
                            class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full"
                            placeholder="New password" />
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-600">Confirm new password</label>
                        <input type="password"
                            class="bg-gray-50 border border-gray-200 text-gray-600 rounded px-3 py-2 w-full"
                            placeholder="Confirm new password" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="mt-[10px] text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-1/3 cursor-pointer">
                            Change password
                        </button>
                    </div>
                </form>

                <!-- Logout and Delete Account -->
                <div class="flex gap-4 mt-10">
                    <!-- Delete Account -->
                    <form id="delete-account-form" action="{{ route('deleteUserAccount') }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end">
                            <button type="submit"
                                class="mt-5 flex items-center justify-center gap-2 px-5 py-2.5 bg-transparent text-red-600 hover:text-red-400 rounded-lg font-medium text-sm focus:outline-none focus:ring-2 focus:ring-red-100 w-full transition-colors cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Delete Account
                            </button>
                        </div>
                    </form>
                    <!-- Logout -->
                    <form id="logout-form-account" action="/logout" method="POST" class="flex-1">
                        @csrf
                        <div class="flex justify-end">
                            <button type="submit"
                                class="mt-5 flex items-center justify-center gap-2 px-5 py-2.5 bg-transparent text-red-600 hover:text-red-400 rounded-lg font-medium text-sm focus:outline-none focus:ring-2 focus:ring-red-100 w-full transition-colors cursor-pointer">
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
    </div>

    <!-- Hidden logout form -->
    <form id="logout-form" action="/logout" method="POST" style="display: none;">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form-account');
            const deleteAccountForm = document.getElementById('delete-account-form');

            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const token = localStorage.getItem('token');
                    fetch('/logout', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                localStorage.clear();
                                window.location.href = '/page_login';
                            } else {
                                response.json().then(data => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error logging out',
                                        text: data.message ||
                                            'An unexpected error occurred.',
                                        showConfirmButton: true
                                    });
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error logging out:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error logging out',
                                text: 'An unexpected error occurred.',
                                showConfirmButton: true
                            });
                        });
                });
            }

            if (deleteAccountForm) {
                deleteAccountForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone. Your account and all associated data will be permanently deleted.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete my account!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const token = localStorage.getItem('token');
                            fetch('/delete_account', {
                                    method: 'DELETE',
                                    headers: {
                                        'Authorization': `Bearer ${token}`,
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'input[name="_token"]').value,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (response.ok) {
                                        localStorage.clear();
                                        Swal.fire(
                                            'Deleted!',
                                            'Your account has been permanently deleted.',
                                            'success'
                                        ).then(() => {
                                            window.location.href = '/page_login';
                                        });
                                    } else {
                                        response.json().then(data => {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error deleting account',
                                                text: data.message ||
                                                    'An unexpected error occurred.',
                                                showConfirmButton: true
                                            });
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deleting account:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error deleting account',
                                        text: 'An unexpected error occurred.',
                                        showConfirmButton: true
                                    });
                                });
                        }
                    });
                });
            }
        });

        document.getElementById('logout-link')?.addEventListener('click', function(e) {
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
