<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Blog</title>
    <link rel="icon" type="image/x-icon" href="https://www.svgrepo.com/show/475713/blog.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100" style="height: 100vh">
    <section class="flex justify-center items-center" style="height: 100vh">
        <div class="flex flex-col items-center px-6 py-10 mx-auto w-full">
            <div
                class="w-full bg-white rounded-2xl shadow-2xl border border-gray-200 md:mt-0 sm:max-w-md xl:p-0 transition-all duration-300" >
                <div class="p-10 space-y-6 flex flex-col justify-center h-full">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-600 md:text-2xl">
                        Sign in to your account
                    </h1>
                    <form id="loginForm" class="space-y-4 md:space-y-6">
                        @csrf
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-600">Your email</label>
                            <input type="email" name="email" id="email"
                                class="bg-white border border-gray-200 text-gray-600 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required="">
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-600">Password</label>
                            <input type="password" name="password" id="password"
                                class="bg-white border border-gray-200 text-gray-600 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required="">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" aria-describedby="remember" type="checkbox"
                                        class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                                        required="">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500">Remember me</label>
                                </div>
                            </div>
                            <a href="#"
                                class="text-sm font-medium text-blue-600 hover:underline">Forgot
                                password?</a>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign
                            in</button>
                        <p class="text-sm font-light text-gray-500">
                            Don't have an account yet? <a href="{{ route('signup') }}"
                                class="font-medium text-blue-600 hover:underline">Sign up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </div>

</body>

</html>
