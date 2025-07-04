<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blog</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- <h1 class="text-3xl font-bold text-blue-500">Hello Tailwind!</h1> --}}
    @include('header')
    @php
        $user = Auth::user();
    @endphp
    <p>{{ $user->name }}</p>
    <button onclick="console.log(localStorage.getItem('token'))">check token</button>
    <button onclick="console.log({{$user}})">check auth</button>
</body>

</html>