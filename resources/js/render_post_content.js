// Hàm renderPostContent: render nội dung EditorJS JSON ra div
export function renderPostContent(holderId, contentData) {
    if (!contentData || !contentData.blocks) return;
    const holder = document.getElementById(holderId);
    if (!holder) return;
    let html = '';
    contentData.blocks.forEach(block => {
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
}
window.renderPostContent = renderPostContent;

if (window.contentData && document.getElementById('editorjs-render')) {
    window.renderPostContent('editorjs-render', window.contentData);
} 