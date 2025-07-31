<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-4 hover:shadow-md transition-shadow">
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
                @if(auth()->check() && auth()->id() === $comment->user_id)
                    <button onclick="deleteComment({{ $comment->id }})" 
                            class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50"
                            title="Delete comment">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                @endif
            </div>
            
            <!-- Comment Text -->
            <div class="text-sm text-gray-700 leading-relaxed">
                {{ $comment->comment }}
            </div>
            
            <!-- Actions Row -->
            <div class="flex items-center space-x-4 mt-3">
                <button onclick="likeComment({{ $comment->id }})" 
                        class="flex items-center space-x-1 text-xs text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                    <span id="like-count-{{ $comment->id }}">{{ $comment->likes_count ?? 0 }}</span>
                </button>
                
                <button onclick="replyToComment({{ $comment->id }})" 
                        class="flex items-center space-x-1 text-xs text-gray-500 hover:text-green-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>Reply</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteComment(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove comment from DOM
                const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                if (commentElement) {
                    commentElement.remove();
                }
                // Show success message
                showNotification('Comment deleted successfully!', 'success');
            } else {
                showNotification(data.message || 'Failed to delete comment', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting the comment', 'error');
        });
    }
}

function likeComment(commentId) {
    fetch(`/comments/${commentId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeCount = document.getElementById(`like-count-${commentId}`);
            if (likeCount) {
                likeCount.textContent = data.likes_count;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function replyToComment(commentId) {
    // Focus on reply input or show reply form
    const replyInput = document.getElementById(`reply-input-${commentId}`);
    if (replyInput) {
        replyInput.focus();
        replyInput.style.display = 'block';
    }
}

function showNotification(message, type) {
    // Simple notification function - you can replace with your preferred notification library
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