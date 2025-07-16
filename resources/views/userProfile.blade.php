<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>User Profile - Blog</title>
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
        }

        .post-title-hover:hover {
            color: #2563eb;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    @include('header')
    {{-- @if (request('private_profile'))
        <div class="max-w-2xl mx-auto mt-8">
            <div class="w-full bg-white border border-gray-200 text-gray-700 p-8 rounded-lg shadow text-center text-lg font-semibold">
                This account is <span class="font-bold">private</span>.<br>You need to follow to see this user's posts.
            </div>
        </div>
    @endif --}}
    <div class="flex flex-row justify-center w-full relative gap-6">
        <!-- Account Info (bên trái) -->
        <div class="max-w-xs w-full mt-8 p-8 bg-white rounded-lg shadow-md flex flex-col items-center gap-8 mx-10 mr-4">
            <!-- Avatar -->
            <div class="flex flex-col items-center w-full">
                <img src="{{ $user->avatar ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                    class="w-32 h-32 rounded-full object-cover border-2 border-gray-300 mb-4" alt="Avatar">
            </div>
            <!-- User name & email + Follow button -->
            <div class="flex flex-col items-center mb-2 w-full">
                <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                <span class="text-gray-500 text-base mb-2">{{ $user->email }}</span>
                <span id="private-badge" style="display:none" class="text-xs text-red-500 font-semibold mb-2">This
                    account is private</span>
                @if($already_followed)
                    <button id="unfollow-btn" class="bg-gray-400 cursor-pointer text-white font-semibold px-6 py-2 rounded-full shadow transition text-sm mt-2 w-32">Following</button>
                @elseif($request_sent)
                    <button id="revoke-request-btn" class="bg-gray-400 cursor-pointer text-white font-semibold px-6 py-2 rounded-full shadow transition text-sm mt-2 w-32">Pending</button>
                @elseif($can_request_again)
                    <button id="request-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-full shadow transition text-sm mt-2 w-32">Request</button>
                @else
                    <button id="follow-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-full shadow transition text-sm mt-2 w-32">Follow</button>
                @endif
            </div>
            <!-- Follower/Following row -->
            <div class="flex flex-row justify-center gap-12 w-full">
                <div class="flex flex-row gap-8">
                    <a href="#" id="followers-link" class="text-center">
                        <div class="text-lg font-bold text-gray-800">{{ $user->followers_count ?? 0 }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Followers</div>
                    </a>
                    <a href="#" id="following-link" class="text-center">
                        <div class="text-lg font-bold text-gray-800">{{ $user->following_count ?? 0 }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Following</div>
                    </a>
                </div>
            </div>
        </div>
        @if (request('private_profile'))
        <div id="private-notice"
            class="w-full text-gray-700 p-8 rounded-lg text-start text-lg font-semibold mt-6 min-h-[180px] mt-12">
            This account is <span class="font-bold mx-1">private</span>. <br>
            You need to follow to see this user's posts.
        </div>
        @endif

        <!-- Main content (bên phải) -->
        <div class="flex-1 flex flex-col gap-6 mt-8 mx-10 ml-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="posts-row-all"></div>
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
    <!-- Modal Privacy (chỉ hiển thị, không cho chỉnh sửa) -->
    <div id="modal-privacy"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/75 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0 w-full">
            <div id="modal-privacy-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Privacy</h3>
                    <div class="text-gray-700 text-sm leading-relaxed">
                        <p class="mb-4 text-left">
                            Users who set their account privacy to <span
                                class="font-semibold text-gray-900">Private</span> must approve each follow request
                            manually, meaning other users need to send a follow request and wait for approval.<br>
                            In contrast, users with <span class="font-semibold text-gray-900">Public</span> accounts can
                            be followed instantly without requiring any approval.
                        </p>
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

    <div class="w-full h-35"></div>

    @if (!$private_profile)
        <script>
            // Truyền biến từ PHP sang JS
            const PRIVATE_PROFILE = @json($private_profile);

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

                // Logic kiểm tra private_profile bằng JS
                if (PRIVATE_PROFILE) {
                    document.getElementById('posts-row-all').style.display = 'none';
                    document.getElementById('private-badge').style.display = '';
                    return;
                } else {
                    document.getElementById('posts-row-all').style.display = '';
                    document.getElementById('private-badge').style.display = 'none';
                }

                // Fetch bài viết nếu không private
                fetch(`/content-of-author/{{ $user->id }}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(posts => {
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
                            let coverImg = post.additionFile ? post.additionFile :
                                '/images/free-images-for-blog.png';
                            // Tạo khung bài viết (không có nút actions)
                            const postDiv = document.createElement('div');
                            postDiv.className = 'bg-white rounded-lg shadow p-4 flex flex-col relative';
                            postDiv.style.minHeight = '120px';
                            postDiv.style.maxHeight = '216px';
                            postDiv.style.overflow = 'hidden';
                            postDiv.innerHTML = `
                        <div class="flex flex-row items-center mb-2 justify-between">
                            <div class="flex items-center gap-3">
                                <img src="${coverImg}" alt="cover" class="w-22 h-22 object-cover rounded-md border border-gray-200 bg-gray-100" style="aspect-ratio:1/1;">
                                <a href="/post-content-viewer/${post.id}" class="font-bold text-lg text-gray-800 cursor-pointer post-title-hover hover:underline">${post.title || 'lỗi gì đó'}</a>
                            </div>  
                        </div>
                        <div class="flex flex-row items-center gap-2 mb-2 cursor-pointer">
                            <span class="inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold cursor-pointer">${categoryName}</span>
                            <span class="px-2 py-1 rounded text-xs ${post.status === 'public' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'} cursor-pointer">${status}</span>
                        </div>
                        <div class="text-xs text-gray-400 mt-auto cursor-pointer">${createdAt}</div>
                        `;
                            postsList.appendChild(postDiv);
                        });
                    });
            });
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.30.8/dist/editorjs.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const followBtn = document.getElementById('follow-btn');
        if (followBtn) {
            followBtn.addEventListener('click', function() {
                const userId = {{ $user->id }};
                fetch(`/follow_user/${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.type === 'request') {
                                // Đổi nút thành Pending (có thể bấm để thu hồi)
                                followBtn.textContent = 'Pending';
                                followBtn.id = 'revoke-request-btn';
                                followBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                                followBtn.classList.add('bg-gray-400', 'cursor-pointer');
                                followBtn.disabled = false;
                                // Gắn lại event thu hồi request
                                followBtn.addEventListener('click', function revokeHandler() {
                                    fetch(`/revoke_follow_request/${userId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            alert(data.message || 'Revoke request failed!');
                                        }
                                    })
                                    .catch(() => {
                                        alert('Revoke request failed!');
                                    });
                                }, { once: true });
                            } else if (data.type === 'follow') {
                                followBtn.textContent = 'Following';
                                followBtn.id = 'unfollow-btn';
                                followBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                                followBtn.classList.add('bg-gray-400', 'cursor-default');
                                followBtn.disabled = true;
                            }
                        } else {
                            alert(data.message || 'Follow failed!');
                        }
                    })
                    .catch(() => {
                        alert('Follow failed!');
                    });
                    location.reload();
            });
        }
        const unfollowBtn = document.getElementById('unfollow-btn');
        if (unfollowBtn) {
            unfollowBtn.addEventListener('click', function() {
                const userId = {{ $user->id }};
                fetch(`/delete_follow/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Reload lại trang để cập nhật trạng thái
                        location.reload();
                    } else {
                        alert(data.message || 'Unfollow failed!');
                    }
                })
                .catch(() => {
                    alert('Unfollow failed!');
                });
            });
        }
        const revokeRequestBtn = document.getElementById('revoke-request-btn');
        if (revokeRequestBtn) {
            revokeRequestBtn.addEventListener('click', function() {
                const userId = {{ $user->id }};
                fetch(`/revoke_follow_request/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Revoke request failed!');
                    }
                })
                .catch(() => {
                    alert('Revoke request failed!');
                });
            });
        }
    });

</script>

</html>
@include('footer')
