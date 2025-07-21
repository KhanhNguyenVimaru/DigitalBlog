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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .editorjs-content p {
            font-size: 1.08rem;
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }

        .editorjs-content h1,
        .editorjs-content h2,
        .editorjs-content h3 {
            font-size: 1.35rem;
            margin-top: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .editorjs-content img {
            max-height: 120px;
            object-fit: cover;
        }

        .editorjs-content ul,
        .editorjs-content ol {
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        .post-title-hover {
            transition: color 0.2s;
            cursor: pointer;
        }

        .post-title-hover:hover {
            color: #2563eb;
            text-decoration: none !important;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    @include('header')
    <div class="flex flex-row justify-center w-full gap-6">
        <!-- Account Info -->
        <div class="max-w-xs w-full mt-8 p-8 bg-white rounded-lg shadow-md flex flex-col items-center gap-8 mx-10 mr-4">
            <!-- Avatar -->
            <div class="flex flex-col items-center w-full">
                <img src="{{ $user->avatar ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                    class="w-32 h-32 rounded-full object-cover border-2 border-gray-300 mb-4" alt="Avatar">
            </div>
            <!-- Privacy + Edit profile row -->
            <div class="flex flex-row items-center gap-4 mb-2">
                @if ($user->privacy === 'public')
                    <span class="px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700 cursor-pointer" style="cursor: pointer;">{{ $user->privacy }}</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs bg-gray-200 text-gray-700 cursor-pointer" style="cursor: pointer;">{{ $user->privacy }}</span>
                @endif
                <a href="{{ route('account') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-1 rounded transition text-sm">Edit profile</a>
            </div>
            <!-- User name & email -->
            <div class="flex flex-col items-center mb-2">
                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <span class="text-base text-gray-500">{{ $user->email }}</span>
            </div>
            <!-- Follower/Following row -->
            <div class="flex flex-row justify-center gap-12 w-full">
                <div class="flex flex-row gap-8">
                    <a href="#" id="followers-link" class="text-center">
                        <div class="text-lg font-bold text-gray-800">{{ $count_follower ?? 0 }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Followers</div>
                    </a>
                    <a href="#" id="following-link" class="text-center">
                        <div class="text-lg font-bold text-gray-800">{{ $count_following ?? 0 }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Following</div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Grid for Posts (1 hàng ngang, tự wrap) -->
        <div class="flex-1 flex flex-col gap-6 mt-8 mx-10 ml-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="posts-row-all"></div>
        </div>
    </div>

    <!-- Modal Followers -->
    <div id="modal-followers"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/75 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0 w-full">
            <div id="modal-followers-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-1/2 sm:max-w-2xl opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Followers</h3>
                    @if($followers->isEmpty())
                        <div class="text-gray-500 text-lg">No followers yet.</div>
                    @else
                        @foreach($followers as $item)
                            @php $f = $item->follower; @endphp
                            <a href="{{ route('userProfile', ['id' => $f->id]) }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition rounded cursor-pointer">
                                <img src="{{ $f->avatar ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg' }}" class="w-14 h-14 rounded-full object-cover border" alt="avatar">
                                <div class="flex flex-col">
                                    <span class="font-bold text-lg text-gray-800">{{ $f->name }}</span>
                                    <span class="text-base text-gray-500">{{ $f->email }}</span>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button"
                        class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-base font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 cursor-pointer"
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
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-1/2 sm:max-w-2xl opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Following</h3>
                    @if($following->isEmpty())
                        <div class="text-gray-500 text-lg">No following yet.</div>
                    @else
                        @foreach($following as $item)
                            @php $a = $item->author; @endphp
                            <a href="{{ route('userProfile', ['id' => $a->id]) }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition rounded cursor-pointer">
                                <img src="{{ $a->avatar ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg' }}" class="w-14 h-14 rounded-full" alt="avatar">
                                <div class="flex flex-col">
                                    <span class="font-bold text-lg text-gray-800">{{ $a->name }}</span>
                                    <span class="text-base text-gray-500">{{ $a->email }}</span>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button"
                        class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-base font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 cursor-pointer"
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
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Privacy</h3>
                    <!-- Nội dung modal privacy -->
                    <div class="text-gray-700 text-sm leading-relaxed">
                        <p class="mb-4 text-left">
                            Users who set their account privacy to <span
                                class="font-semibold text-gray-900">Private</span> must approve each follow request
                            manually, meaning other users need to send a follow request and wait for approval.<br>
                            In contrast, users with <span class="font-semibold text-gray-900">Public</span> accounts can
                            be followed instantly without requiring any approval.
                        </p>
                        <span class="text-left block text-sm">Want to reset your privacy? <a href="/page_account"
                                class="text-indigo-600 font-semibold hover:underline transition">Setting here</a></span>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button"
                        class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 cursor-pointer"
                        onclick="closeModal('modal-privacy')">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách bài viết của user -->
    <div id="user-posts" class="max-w-6xl mx-auto mt-8">
        <div id="posts-list" class="w-11/12 md:w-full mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 justify-between">
        </div>
    </div>
    <div class="w-full h-20"></div>

    <!-- Spinner loading khi xóa -->
    <div id="delete-spinner" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:999;background:rgba(255,255,255,0.7);justify-content:center;align-items:center;">
      <div class="spinner" style="border: 6px solid #f3f3f3; border-top: 6px solid #2563eb; border-radius: 50%; width: 60px; height: 60px; animation: spin 1s linear infinite;"></div>
    </div>
    <style>
    @keyframes spin {
      0% { transform: rotate(0deg);}
      100% { transform: rotate(360deg);}
    }
    </style>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            const panel = document.getElementById(id + '-panel');
            if (!modal) return;
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.classList.add('opacity-100');
            setTimeout(() => {
                if (panel) {
                    panel.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
                    panel.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                }
            }, 10);
            modal.addEventListener('mousedown', function handler(e) {
                if (panel && !panel.contains(e.target)) {
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
                // Fetch followers
                // Xoá đoạn fetch API followers/following trong JS
            });
            document.getElementById('following-link').addEventListener('click', function(e) {
                e.preventDefault();
                openModal('modal-following');
                // Fetch following
                // Xoá đoạn fetch API followers/following trong JS
            });
            var privacyBadge = document.querySelector('.bg-indigo-100.text-indigo-700, .bg-gray-200.text-gray-700');
            if (privacyBadge) {
                privacyBadge.style.cursor = 'pointer';
                privacyBadge.addEventListener('click', function(e) {
                    openModal('modal-privacy');
                });
            }

            // Lấy bài viết của user
            fetch('/content-of-users', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(posts => {
                    // Hiển thị số lượng post lên mục Posts (nếu còn dùng ở nơi khác)
                    const postsCount = posts.length;
                    const postsCountEls = document.querySelectorAll('.js-posts-count');
                    postsCountEls.forEach(el => el.textContent = postsCount);
                    const postsList = document.getElementById('posts-row-all');
                    postsList.innerHTML = '';
                    if (!posts.length) {
                        postsList.innerHTML = '<div class="text-gray-500">No posts yet.</div>';
                        return;
                    }
                    posts.forEach(post => {
                        let categoryName = post.category ? post.category.content : 'No category';
                        let status = post.status.charAt(0).toUpperCase() + post.status.slice(1);
                        let createdAt = new Date(post.created_at).toLocaleString();
                        let coverImg = post.additionFile ? post.additionFile : '/images/free-images-for-blog.png';

                        // Tạo khung bài viết
                        const postDiv = document.createElement('div');
                        postDiv.className = 'bg-white rounded-lg shadow p-4 flex flex-col relative';
                        postDiv.style.minHeight = '120px';
                        postDiv.style.maxHeight = '216px';
                        postDiv.style.overflow = 'hidden';
                        // Tạo id duy nhất cho modal và button
                        const modalId = `modal-post-actions-${post.id}`;
                        const modalPanelId = `modal-post-actions-panel-${post.id}`;
                        const btnId = `post-actions-btn-${post.id}`;
                        // Header bài viết chỉ còn title và nút actions
                        postDiv.innerHTML = `
                        <div class="flex flex-row items-center mb-2 justify-between">
                            <div class="flex items-center gap-3">
                                <img src="${coverImg}" alt="cover" class="w-22 h-22 object-cover rounded-md border border-gray-200 bg-gray-100" style="aspect-ratio:1/1;">
                                <a href="/post-content-viewer/${post.id}" class="font-bold text-base text-gray-800 cursor-pointer post-title-hover">${post.title || 'lỗi gì đó'}</a>
                            </div>
                            <div class="relative cursor-pointer">
                                <button id="${btnId}" class="p-2 rounded-full hover:bg-gray-200 focus:outline-none cursor-pointer" title="Actions" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500 cursor-pointer">
                                        <path cursor-pointer stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 7.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 7.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
                                    </svg>
                                </button>
                                <div id="dropdown-${post.id}" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-lg z-50 cursor-pointer z-index-99">
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 text-gray-800 change-status-btn cursor-pointer" data-post-id="${post.id}" data-current-status="${post.status}">
                                        Set status: ${post.status === 'public' ? 'Private' : 'Public'}
                                    </button>
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm hover:bg-red-100 text-red-600 delete-post-btn cursor-pointer" data-post-id="${post.id}">
                                        Delete post
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row items-center justify-between mb-2 w-full">
                            <div class="flex flex-row items-center gap-2">
                                <span class="inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold cursor-pointer">${categoryName}</span>
                                <span class="px-2 py-1 rounded text-xs ${post.status === 'public' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'} cursor-pointer">${status}</span>
                            </div>
                            <div class="text-xs text-gray-400 cursor-pointer">${createdAt}</div>
                        </div>
                        `;
                        postsList.appendChild(postDiv);
                        // Sự kiện mở dropdown cho từng post
                        setTimeout(() => {
                            const btn = document.getElementById(btnId);
                            const dropdown = document.getElementById(`dropdown-${post.id}`);
                            if (btn && dropdown) {
                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    // Đóng tất cả dropdown khác
                                    document.querySelectorAll('[id^="dropdown-"]')
                                        .forEach(el => {
                                            if (el !== dropdown) el.classList.add(
                                                'hidden');
                                        });
                                    dropdown.classList.toggle('hidden');
                                });
                                // Đóng dropdown khi click ra ngoài
                                document.addEventListener('mousedown', function handler(event) {
                                    if (!btn.contains(event.target) && !dropdown
                                        .contains(event.target)) {
                                        dropdown.classList.add('hidden');
                                    }
                                });
                            }
                            // Sự kiện đổi status
                            const statusBtn = dropdown.querySelector('.change-status-btn');
                            if (statusBtn) {
                                statusBtn.addEventListener('click', function() {
                                    const postId = this.getAttribute('data-post-id');
                                    if (!postId) return;
                                    fetch(`/update-status/${postId}`, {
                                        method: 'PATCH',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Updated!',
                                                text: data.message || 'Status updated.',
                                                showConfirmButton: false,
                                                timer: 1000
                                            }).then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error!',
                                                text: data.message || 'Update failed!'
                                            });
                                        }
                                    })
                                    .catch(() => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Update failed!'
                                        });
                                    });
                                    dropdown.classList.add('hidden');
                                });
                            }
                            // Sự kiện xóa post
                            const deleteBtn = dropdown.querySelector('.delete-post-btn');
                            if (deleteBtn) {
                                deleteBtn.addEventListener('click', function() {
                                    // Gửi SweetAlert xác nhận xóa
                                    const postId = this.getAttribute('data-post-id');
                                    if (!postId) return;
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: 'This action cannot be undone!',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Yes, delete it!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            fetch(`/delete-post/${postId}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'X-CSRF-TOKEN': document
                                                            .querySelector(
                                                                'meta[name="csrf-token"]'
                                                                )
                                                            .getAttribute(
                                                                'content'),
                                                        'Accept': 'application/json'
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Deleted!',
                                                            text: data.message || 'Post has been deleted.',
                                                            showConfirmButton: false,
                                                            timer: 1000
                                                        }).then(() => {
                                                            location.reload();
                                                        });
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error!',
                                                            text: data
                                                                .message ||
                                                                'Delete failed!'
                                                        });
                                                    }
                                                })
                                                .catch(() => {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error!',
                                                        text: 'Delete failed!'
                                                    });
                                                });
                                        }
                                    });
                                    dropdown.classList.add('hidden');
                                });
                            }
                        }, 0);
                    });
                });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.30.8/dist/editorjs.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('footer')
</body>

</html>