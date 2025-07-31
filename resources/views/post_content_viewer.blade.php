    @php
        $json = is_string($content) ? $content : json_encode($content);

    @endphp
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $title }} - Content Viewer</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg" />
        @vite(['resources/css/app.css'])
        <style>
            .editorjs-content {
                font-size: 1.15rem;
                letter-spacing: 0.02em;
            }

            .editorjs-content p {
                font-size: 1.15rem;
                line-height: 1.7;
                margin-bottom: 0.9rem;
                letter-spacing: 0.02em;
            }

            .editorjs-content h1 {
                font-size: 2rem;
                letter-spacing: 0.02em;
            }

            .editorjs-content h2 {
                font-size: 1.5rem;
                letter-spacing: 0.02em;
            }

            .editorjs-content h3 {
                font-size: 1.25rem;
                letter-spacing: 0.02em;
            }

            .editorjs-content img {
                max-width: 100%;
                object-fit: cover;
                margin: 1rem 0;
                border-radius: 0.5rem;
            }

            .editorjs-content ul,
            .editorjs-content ol {
                font-size: 1.15rem;
                margin-left: 1.2rem;
                margin-bottom: 0.9rem;
                letter-spacing: 0.02em;
            }
        </style>
    </head>

    <body class="bg-gray-100 min-h-screen">
        @include('header')
        @include('components.breadcrumb', [
            'links' => \App\Http\Controllers\Controller::generateBreadcrumbLinks(),
        ])
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8 mt-0 pt-0 mb-10">
            @if ($category)
                <a
                    href="#"class="text-xs text-blue-600 hover:underline font-semibold mb-4 inline-block uppercase tracking-wide my-4">{{ $category }}</a>
            @else
                <a
                    href="#"class="text-xs text-blue-600 hover:underline font-semibold mb-4 inline-block uppercase tracking-wide my-4">NO
                    CATEGORY</a>
            @endif
            <h1 class="text-4xl font-bold mb-4 text-gray-900 text-center leading-tight">{{ $title }}</h1>
            <div class="flex items-center justify-start gap-3 mb-8">
                <img src="{{ $author_avatar }}" alt="avatar"
                    class="w-10 h-10 rounded-full object-cover border border-gray-300">
                <div class="flex flex-col">
                    <span class="text-base text-gray-800 font-semibold">{{ $author_name }}</span>
                    <span class="text-xs text-gray-400 font-normal">{{ $created_at ?? '' }}</span>
                </div>
            </div>
            <div class="editorjs-content text-gray-800" id="editorjs-render"></div>
        </div>

        <!-- Comments Section -->
        <div class="max-w-4xl mx-auto mt-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-5">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Comments ({{ count($comments) }})</h3>

                <!-- Comment Form -->
                @auth
                    <div class="mb-6">
                        <form id="comment-form" class="space-y-4">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post_id }}">
                            <div>
                                <textarea name="comment" id="comment-text" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    placeholder="Write your comment here..." required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="cursor-pointer px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-gray-600">Please <a href="{{ route('login') }}"
                                class="text-blue-600 hover:underline">login</a> to leave a comment.</p>
                    </div>
                @endauth

                <!-- Comments List -->
                <div id="comments-container" class="space-y-4">
                    @forelse($comments as $comment)
                        <div data-comment-id="{{ $comment->id }}"
                            class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                            <div class="flex items-start space-x-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <img src="{{ $comment->user->avatar ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                                        alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                </div>

                                <!-- Comment Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Header: Name, Time, Actions -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $comment->user->name }}
                                            </h4>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                            </span>
                                        </div>

                                        <!-- Delete Button (only for comment owner) -->
                                        @if (auth()->check() && auth()->id() === $comment->user_id)
                                            <button onclick="deleteComment({{ $comment->id }})"
                                                class="cursor-pointer text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50"
                                                title="Delete comment">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Comment Text -->
                                    <div class="text-sm text-gray-700 leading-relaxed">
                                        {{ $comment->comment }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p>No comments yet. Be the first to comment!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function renderPostContent(holderId, contentData) {
                if (!contentData || !contentData.blocks) return;
                const holder = document.getElementById(holderId);
                if (!holder) return;
                let html = '';
                contentData.blocks.forEach(block => {
                    switch (block.type) {
                        case 'header':
                            html +=
                                `<h${block.data.level} class="font-bold mt-4 mb-2">${block.data.text}</h${block.data.level}>`;
                            break;
                        case 'paragraph':
                            html += `<p class="mb-3">${block.data.text}</p>`;
                            break;
                        case 'list':
                            if (block.data.style === 'ordered') {
                                html += '<ol class="list-decimal ml-6 mb-3">';
                                block.data.items.forEach(item => html += `<li>${item}</li>`);
                                html += '</ol>';
                            } else {
                                html += '<ul class="list-disc ml-6 mb-3">';
                                block.data.items.forEach(item => html += `<li>${item}</li>`);
                                html += '</ul>';
                            }
                            break;
                        case 'image':
                            html +=
                                `<div style=\"width:100%;display:flex;justify-content:center;align-items:center;\"><img src=\"${block.data.file.url}\" alt=\"\" class=\"my-4 mb-5 rounded\" style=\"width:90%;height:auto;object-fit:cover;display:block;\"></div>`;
                            break;
                        case 'quote':
                            html +=
                                `<blockquote class="border-l-4 border-blue-400 pl-4 italic text-gray-600 my-4">${block.data.text}<br><span class="block text-xs text-gray-400 mt-1">${block.data.caption || ''}</span></blockquote>`;
                            break;
                        case 'checklist':
                            html += '<ul class="ml-6 mb-3">';
                            block.data.items.forEach(item => {
                                html +=
                                    `<li><input type="checkbox" disabled ${item.checked ? 'checked' : ''}> ${item.text}</li>`;
                            });
                            html += '</ul>';
                            break;
                        case 'raw':
                            html +=
                            `<pre class="bg-gray-100 rounded p-2 overflow-x-auto mb-3">${block.data.html}</pre>`;
                            break;
                        case 'simpleImage':
                            html +=
                                `<div style=\"width:100%;display:flex;justify-content:center;align-items:center;\"><img src=\"${block.data.url}\" alt=\"\" class=\"my-4 mb-5 rounded\" style=\"width:90%;height:auto;object-fit:cover;display:block;\"></div>`;
                            break;
                        default:
                            break;
                    }
                });
                holder.innerHTML = html;
            }
            window.contentData = @json(json_decode($json));
            if (window.contentData && document.getElementById('editorjs-render')) {
                renderPostContent('editorjs-render', window.contentData);
            }

            // Handle comment form submission
            document.getElementById('comment-form')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const commentText = formData.get('comment').trim();

                if (!commentText) {
                    alert('Please enter a comment');
                    return;
                }

                fetch('/comments', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            post_id: formData.get('post_id'),
                            comment: commentText
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear form
                            document.getElementById('comment-text').value = '';

                            // Reload page to show new comment
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to post comment');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while posting the comment');
                    });
            });

            function deleteComment(commentId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this comment?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/comments/${commentId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const commentElement = document.querySelector(
                                        `[data-comment-id="${commentId}"]`);
                                    if (commentElement) {
                                        commentElement.remove();
                                    }

                                    const commentCount = document.querySelector('h3').textContent.match(
                                    /\((\d+)\)/);
                                    if (commentCount) {
                                        const newCount = parseInt(commentCount[1]) - 1;
                                        document.querySelector('h3').textContent = `Comments (${newCount})`;
                                    }

                                    Swal.fire('Deleted!', 'Comment deleted successfully!', 'success');
                                } else {
                                    Swal.fire('Failed', data.message || 'Failed to delete comment', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'An error occurred while deleting the comment', 'error');
                            });
                    }
                });
            }


            function showNotification(message, type) {
                // Simple notification function
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        </script>
        @include('footer')
    </body>

    </html>
