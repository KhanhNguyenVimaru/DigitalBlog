<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>New Post - Blog</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    @include('header')
    <div class="min-h-screen bg-gray-100 flex flex-col items-center py-10">
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Create New Post</h2>
            <form method="POST" action="#" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Post Title</label>
                    <input type="text" name="title" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter post title..." required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" rows="8" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-y" placeholder="Enter post content..." required></textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Attachment (optional)</label>
                    <input type="file" name="additionFile" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow transition">Post</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
