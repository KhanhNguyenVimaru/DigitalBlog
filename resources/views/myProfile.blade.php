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
    <div class="flex justify-center w-full">
        <div class="max-w-4xl w-full mt-8 p-8 bg-white rounded-lg shadow-md flex flex-col md:flex-row items-start gap-8">
            <!-- Avatar -->
            <div class="flex flex-col items-center md:items-start w-full md:w-1/3 pl-30">
                <img src="{{ $user->avatar ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                    class="w-32 h-32 rounded-full object-cover border-2 border-gray-300 mb-4" alt="Avatar">
            </div>
            <!-- Info + Actions -->
            <div class="flex-1 flex flex-col items-start gap-4 w-auto pl-5">
                <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 w-auto">
                    <div class="flex flex-col items-center md:items-start">
                        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                        <span class="text-gray-500 text-base">{{ $user->email }}</span>
                    </div>
                    @if ($user->privacy === 'public')
                        <span class="px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700" style="cursor: pointer;">{{ $user->privacy }}</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs bg-gray-200 text-gray-700" style="cursor: pointer;">{{ $user->privacy }}</span>
                    @endif
                    <a href="{{ route('account') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-1 rounded transition text-sm">Edit profile</a>
                </div>
                @if ($user->description)
                    <div class="text-gray-600 text-base text-center md:text-left">{{ $user->description }}</div>
                @endif
                <!-- Follower/Following/Posts row -->
                <div class="flex flex-row justify-start gap-12 mt-2 w-auto">
                    <div class="flex flex-row gap-8">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-800">0</div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Posts</div>
                        </div>
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

    <!-- Danh sách bài viết của user -->
    <div id="user-posts" class="max-w-4xl mx-auto mt-8">
        <h3 class="text-xl font-bold mb-4 text-gray-800">My Posts</h3>
        <div id="posts-list" class="w-11/12 md:w-9/10 mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 justify-between"></div>
    </div>
    <div class="w-full h-20"></div>

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
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.30.8/dist/editorjs.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... các script modal cũ ...

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
                    const postsList = document.getElementById('posts-list');
                    if (!posts.length) {
                        postsList.innerHTML = '<div class="text-gray-500">No posts yet.</div>';
                        return;
                    }
                    posts.forEach(post => {
                        let categoryName = post.category ? post.category.content : 'No category';
                        let status = post.status.charAt(0).toUpperCase() + post.status.slice(1);
                        let createdAt = new Date(post.created_at).toLocaleString();

                        // Tạo khung bài viết hình vuông
                        const postDiv = document.createElement('div');
                        postDiv.className = 'bg-white rounded-lg shadow p-6 flex flex-col';
                        postDiv.style.aspectRatio = '1/1';
                        postDiv.style.minHeight = '250px';
                        postDiv.style.maxHeight = '400px';
                        postDiv.style.overflow = 'hidden';

                        // Tạo id duy nhất cho content
                        const contentId = `post-content-${post.id}`;
                        const expandBtnId = `expand-btn-${post.id}`;

                        // Header bài viết mới: user, category, status, time
                        postDiv.innerHTML = `
                            <div class="flex flex-row items-center mb-2">
                                <a href="/my-profile" class="text-blue-700 font-semibold hover:underline text-sm">${post.author && post.author.name ? post.author.name : (window.userName || 'You')}</a>
                                <div class="flex flex-row items-center gap-2 ml-auto">
                                    <span class="inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">${categoryName}</span>
                                    <span class="px-2 py-1 rounded text-xs ${post.status === 'public' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'}">${status}</span>
                                    <span class="text-xs text-gray-400 ml-2">${createdAt}</span>
                                </div>
                            </div>
                            <div class="font-extrabold text-2xl text-gray-800 mb-2">${post.title || 'lỗi gì đó'}</div>
                            <div id="${contentId}" class="editorjs-content relative transition-all duration-300 flex-1" style="max-height: 150px; overflow: hidden;"></div>
                            <div class='mt-auto flex justify-center'><a href="/post/${post.id}" class="mt-2 text-blue-600 hover:underline text-sm">Show more</a></div>
                        `;

                        postsList.appendChild(postDiv);

                        // Render nội dung EditorJS
                        let contentData = null;
                        try {
                            contentData = typeof post.content === 'string' ? JSON.parse(post.content) : post.content;
                        } catch (e) {
                            contentData = null;
                        }
                        if (contentData && contentData.blocks) {
                            renderEditorJSContent(contentId, contentData, expandBtnId, 150, postDiv);
                        } else {
                            document.getElementById(contentId).innerHTML = '<div class="text-gray-400 italic">No content</div>';
                        }
                    });
                });

        // Sửa hàm renderEditorJSContent để nhận maxHeight động
        function renderEditorJSContent(holderId, data, expandBtnId, maxHeight = 150, postDiv = null) {
            const holder = document.getElementById(holderId);
            if (!holder) return;
            let html = '';
            data.blocks.forEach(block => {
                switch (block.type) {
                    case 'header':
                        html += `<h${block.data.level} class="font-bold mt-2 mb-1">${block.data.text}</h${block.data.level}>`;
                        break;
                    case 'paragraph':
                        html += `<p class="mb-2">${block.data.text}</p>`;
                        break;
                    case 'list':
                        if (block.data.style === 'ordered') {
                            html += '<ol class="list-decimal ml-6">';
                            block.data.items.forEach(item => html += `<li>${item}</li>`);
                            html += '</ol>';
                        } else {
                            html += '<ul class="list-disc ml-6">';
                            block.data.items.forEach(item => html += `<li>${item}</li>`);
                            html += '</ul>';
                        }
                        break;
                    case 'image':
                        html += `<img src="${block.data.file.url}" alt="" class="my-2 rounded max-w-full">`;
                        break;
                    case 'quote':
                        html += `<blockquote class="border-l-4 border-blue-400 pl-4 italic text-gray-600 my-2">${block.data.text}<br><span class="block text-xs text-gray-400 mt-1">${block.data.caption || ''}</span></blockquote>`;
                        break;
                    case 'checklist':
                        html += '<ul class="ml-6">';
                        block.data.items.forEach(item => {
                            html += `<li><input type="checkbox" disabled ${item.checked ? 'checked' : ''}> ${item.text}</li>`;
                        });
                        html += '</ul>';
                        break;
                    case 'raw':
                        html += `<pre class="bg-gray-100 rounded p-2 overflow-x-auto">${block.data.html}</pre>`;
                        break;
                    case 'simpleImage':
                        html += `<img src="${block.data.url}" alt="" class="my-2 rounded max-w-full">`;
                        break;
                    default:
                        break;
                }
            });
            holder.innerHTML = html;

            // Kiểm tra nếu nội dung vượt quá max-height thì hiện nút "Show more"
            setTimeout(() => {
                if (holder.scrollHeight > maxHeight) {
                    const btn = document.getElementById(expandBtnId);
                    if (btn) {
                        btn.classList.remove('hidden');
                        let expanded = false;
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            expanded = !expanded;
                            if (expanded) {
                                holder.style.maxHeight = 'none';
                                holder.style.overflow = 'visible';
                                btn.textContent = 'Show less';
                                if (postDiv) {
                                    postDiv.style.maxHeight = 'none';
                                    postDiv.style.overflow = 'visible';
                                }
                            } else {
                                holder.style.maxHeight = maxHeight + 'px';
                                holder.style.overflow = 'hidden';
                                btn.textContent = 'Show more';
                                if (postDiv) {
                                    postDiv.style.maxHeight = '400px';
                                    postDiv.style.overflow = 'hidden';
                                }
                            }
                        });
                    }
                }
            }, 100);
        }

        });
    </script>
</body>

</html>
