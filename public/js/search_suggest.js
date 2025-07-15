// search_suggest.js

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    let dropdown = null;

    function removeDropdown() {
        if (dropdown) {
            dropdown.remove();
            dropdown = null;
        }
    }

    function showDropdown(suggestions) {
        removeDropdown();
        if (!suggestions.length) return;
        dropdown = document.createElement('div');
        // Căn giữa theo viewport, width 50vw
        dropdown.className = 'fixed left-1/2 transform -translate-x-1/2 mt-2 w-1/2 min-w-[200px] max-w-xl bg-white border border-gray-200 rounded-lg shadow-lg z-50';
        // Tính toán top dựa trên vị trí input (dưới input)
        const rect = searchInput.getBoundingClientRect();
        dropdown.style.top = (window.scrollY + rect.bottom + 8) + 'px';
        dropdown.style.left = '50%';
        dropdown.style.width = '50vw';
        dropdown.innerHTML = suggestions.map((s, i) => {
            let color = '#6b7280'; // default gray for post
            if (s.type === 'user') color = '#2832c2';
            if (s.type === 'group') color = '#60a5fa';
            return `<div class=\"flex flex-row items-center px-4 py-2 hover:bg-gray-100 cursor-pointer\" data-type=\"${s.type}\" data-id=\"${s.id}\" tabindex=\"0\">
                <span class=\"text-xs font-bold uppercase mr-3\" style=\"color:${color}\">${s.type}</span>
                <span class=\"flex-1 text-gray-800\">${s.value}</span>
            </div>`;
        }).join('');
        document.body.appendChild(dropdown);

        // Thêm event click cho từng suggestion
        Array.from(dropdown.children).forEach(item => {
            item.addEventListener('mousedown', function(e) {
                e.preventDefault();
                const type = this.getAttribute('data-type');
                const id = this.getAttribute('data-id');
                if (type === 'user') {
                    window.location.href = `/user-profile/${id}`;
                } else if (type === 'post') {
                    window.location.href = `/post-content-viewer/${id}`;
                } else if (type === 'group') {
                    window.location.href = `/group/${id}`;
                }
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const value = e.target.value.trim();
            if (!value) {
                removeDropdown();
                return;
            }
            fetch(`/search-suggest?q=${encodeURIComponent(value)}`)
                .then(res => res.json())
                .then(suggestions => {
                    showDropdown(suggestions);
                });
        });
        // Ẩn dropdown khi blur
        searchInput.addEventListener('blur', function() {
            setTimeout(removeDropdown, 150); // delay để click chọn suggestion
        });
    }
}); 