<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digital Blog - About</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .filter-link.text-black {
            font-weight: bold;
            border-bottom: 2px solid black;
        }

        .filter-link {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    @include('header')

    <main class="max-w-5xl mx-auto px-4 py-8">
        <!-- About Me -->
        <section class="mb-12 bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4" style="color: rgb(40, 50, 194)">About Me</h2>
            <p class="mb-3">
                Hi — I'm Khanh, a web developer focused on building practical and
                maintainable web applications.
                <em>Digital Blog</em> is my first fully completed project built with <strong>Laravel</strong>, and it
                marks
                a significant milestone in my learning journey.
            </p>
            <p>
                This project helped me practice structuring scalable applications, integrating backend and frontend
                tools,
                and implementing key features like authentication, content management, social interactions, and
                notifications.
            </p>
        </section>

        <!-- Tech Stack -->
        <section class="mb-12 bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4" style="color: rgb(40, 50, 194)">Tech Stack</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    Laravel (PHP)
                </div>
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    MySQL
                </div>
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    Tailwind CSS
                </div>
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    Editor.js
                </div>
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    Cropper.js
                </div>
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    SweetAlert2
                </div>
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    Vanilla JS / Fetch API
                </div>
                <div
                    class="bg-white border border-gray-300 p-3 rounded-lg text-center font-semibold transition duration-300 transform hover:scale-105 hover:border-blue-500 hover:shadow-md">
                    Blade Templates
                </div>
            </div>
        </section>



        <!-- Features -->
        <section class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-blue-600" style="color: rgb(40, 50, 194)">Features</h2>
            <h3 class="font-semibold mt-4 mb-2">Account & Profile</h3>
            <ul class="list-disc list-inside mb-4">
                <li>Register, login, logout, and email verification</li>
                <li>Edit profile, change password, upload avatar with cropping</li>
                <li>Delete account & scheduled cleanup of expired accounts</li>
            </ul>

            <h3 class="font-semibold mb-2">Content & Posts</h3>
            <ul class="list-disc list-inside mb-4">
                <li>Create and edit posts with images and embedded links</li>
                <li>Upload media, set public/private visibility</li>
                <li>Browse posts by category, author, or via API</li>
            </ul>

            <h3 class="font-semibold mb-2">Social & Interaction</h3>
            <ul class="list-disc list-inside mb-4">
                <li>Comment system with create/delete functionality</li>
                <li>Like/unlike posts with counters</li>
                <li>Follow system with request approval and ban/unban options</li>
            </ul>

            <h3 class="font-semibold mb-2">Notifications & Search</h3>
            <ul class="list-disc list-inside">
                <li>In-app notifications for likes, comments, follow requests</li>
                <li>Search suggestions and advanced search</li>
            </ul>
        </section>
    </main>

    @include('footer')
</body>

</html>
