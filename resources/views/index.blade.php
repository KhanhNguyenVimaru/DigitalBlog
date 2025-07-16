<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digital Blog</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- <h1 class="text-3xl font-bold text-blue-500">Hello Tailwind!</h1> --}}
    @include('header')
    <div class = "container mx-auto px-4" style="height: 100vh">
    </div>
    @include('footer')
</body>

</html>