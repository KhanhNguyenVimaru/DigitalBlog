@php
    $json = is_string($content) ? $content : json_encode($content);

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }} - Content Viewer</title>
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
        'links' => \App\Http\Controllers\Controller::generateBreadcrumbLinks()
    ])
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8 mt-0 pt-0 mb-10">
        @if($category)
            <a href="#"class="text-xs text-blue-600 hover:underline font-semibold mb-4 inline-block uppercase tracking-wide my-4">{{ $category }}</a>
        @else
                <a href="#"class="text-xs text-blue-600 hover:underline font-semibold mb-4 inline-block uppercase tracking-wide my-4">NO CATEGORY</a>
        @endif
        <h1 class="text-4xl font-bold mb-4 text-gray-900 text-center leading-tight">{{ $title }}</h1>
        <div class="flex items-center justify-start gap-3 mb-8">
            <img src="{{ $author_avatar }}" alt="avatar" class="w-10 h-10 rounded-full object-cover border border-gray-300">
            <div class="flex flex-col">
                <span class="text-base text-gray-800 font-semibold">{{ $author_name }}</span>
                <span class="text-xs text-gray-400 font-normal">{{ $created_at ?? '' }}</span>
            </div>
        </div>
        <div class="editorjs-content text-gray-800" id="editorjs-render"></div>
    </div>
    <script>
        function renderPostContent(holderId, contentData) {
            if (!contentData || !contentData.blocks) return;
            const holder = document.getElementById(holderId);
            if (!holder) return;
            let html = '';
            contentData.blocks.forEach(block => {
                switch (block.type) {
                    case 'header':
                        html += `<h${block.data.level} class="font-bold mt-4 mb-2">${block.data.text}</h${block.data.level}>`;
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
                        html += `<div style=\"width:100%;display:flex;justify-content:center;align-items:center;\"><img src=\"${block.data.file.url}\" alt=\"\" class=\"my-4 mb-5 rounded\" style=\"width:90%;height:auto;object-fit:cover;display:block;\"></div>`;
                        break;
                    case 'quote':
                        html += `<blockquote class="border-l-4 border-blue-400 pl-4 italic text-gray-600 my-4">${block.data.text}<br><span class="block text-xs text-gray-400 mt-1">${block.data.caption || ''}</span></blockquote>`;
                        break;
                    case 'checklist':
                        html += '<ul class="ml-6 mb-3">';
                        block.data.items.forEach(item => {
                            html += `<li><input type="checkbox" disabled ${item.checked ? 'checked' : ''}> ${item.text}</li>`;
                        });
                        html += '</ul>';
                        break;
                    case 'raw':
                        html += `<pre class="bg-gray-100 rounded p-2 overflow-x-auto mb-3">${block.data.html}</pre>`;
                        break;
                    case 'simpleImage':
                        html += `<div style=\"width:100%;display:flex;justify-content:center;align-items:center;\"><img src=\"${block.data.url}\" alt=\"\" class=\"my-4 mb-5 rounded\" style=\"width:90%;height:auto;object-fit:cover;display:block;\"></div>`;
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
    </script>
    @include('footer')
</body>
</html>
