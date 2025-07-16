<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>New Post - Blog</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/handle_post.js'])
    <style>
        .editor-container {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
            min-height: 100vh;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        #editorjs {
            padding: 2rem;
            height: 100%;
            overflow-y: auto;
        }

        .a4-wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    @include('header')
    <div class="min-h-screen bg-gray-100 flex flex-col items-center py-4">
        <div class="w-full max-w-4xl bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Create New Post</h2>
            <div class="a4-wrapper">
                <label class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                <input id="title" type="text" name="title" class="w-full border border-gray-300 rounded px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter post title..." required>
                <div class="flex flex-row gap-4 mb-4 justify-between">
                    <div class="flex-1">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                        <select id="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" name="status">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                    </div>
                    <div class="flex-1 pl-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Category</label>
                        <select id="categoryId" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" name="categoryId">
                            <option value="">Select a category (optional)</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Cover Image</label>
                    <input id="coverImage" type="file" name="coverImage" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" />
                    <div id="cover-preview" class="mt-2"></div>
                    <button id="remove-cover-btn" type="button" class="mt-2 px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 text-sm font-semibold hidden cursor-pointer">Remove image</button>
                </div>
                <div class="editor-container">
                    <div id="editorjs"></div>
                </div>
                <input type="hidden" name="content" id="content-input">
            </div>


            <div class="flex justify-end m-4 cursor-pointer">
                <button id="post-btn" data-url="{{route('insertPost')}}" type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow transition inline-block text-center">Post</button>
            </div>
        </div>
    </div>
    @include('footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const postBtn = document.getElementById('post-btn');
            const editorElement = document.getElementById('editorjs');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const categorySelect = document.getElementById('categoryId');

            // Load categories
            fetch('/categories', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(categories => {
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.content;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading categories:', error);
            });

            postBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const title = document.getElementById('title').value;
                const status = document.getElementById('status').value;
                const categoryId = document.getElementById('categoryId').value;
                const coverImage = document.getElementById('coverImage').files[0];
                // Lấy content từ EditorJS
                editor.save().then((outputData) => {
                    const formData = new FormData();
                    formData.append('title', title);
                    formData.append('content', JSON.stringify(outputData));
                    formData.append('status', status);
                    if (categoryId) {
                        formData.append('categoryId', categoryId);
                    }
                    if (coverImage) {
                        formData.append('coverImage', coverImage);
                    }

                    fetch(postBtn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData
                    })
                    .then(async response => {
                        const data = await response.json();
                        if(data.success){
                            // Xóa toàn bộ input và EditorJS
                            document.getElementById('title').value = '';
                            document.getElementById('status').selectedIndex = 0;
                            document.getElementById('categoryId').selectedIndex = 0;
                            document.getElementById('coverImage').value = '';
                            document.getElementById('cover-preview').innerHTML = '';
                            if(window.editor) {
                                window.editor.clear();
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Post created successfully!'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Post failed!'
                            });
                        }
                    })
                    .catch(() => {
                        // Xóa toàn bộ input
                        document.getElementById('title').value = '';
                        document.getElementById('status').selectedIndex = 0;
                        document.getElementById('categoryId').selectedIndex = 0;
                        document.getElementById('coverImage').value = '';
                        document.getElementById('cover-preview').innerHTML = '';
                        document.getElementById('remove-cover-btn').classList.add('hidden');
                        if(window.editor) {
                            window.editor.clear();
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred, please try again.'
                        });
                    });
                });
            });
        });
        // Preview cover image
        document.getElementById('coverImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('cover-preview');
            const removeBtn = document.getElementById('remove-cover-btn');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.innerHTML = `<img src="${ev.target.result}" alt="Cover Preview" class="max-h-40 rounded shadow">`;
                };
                reader.readAsDataURL(file);
                removeBtn.classList.remove('hidden');
            } else {
                preview.innerHTML = '';
                removeBtn.classList.add('hidden');
            }
        });
        document.getElementById('remove-cover-btn').addEventListener('click', function() {
            const input = document.getElementById('coverImage');
            const preview = document.getElementById('cover-preview');
            input.value = '';
            preview.innerHTML = '';
            this.classList.add('hidden');
        });
    </script>
</body>

</html>
