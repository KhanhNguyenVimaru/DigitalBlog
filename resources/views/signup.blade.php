<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up - Blog</title>
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
                        Create your account
                    </h1>
                    <form id="signupForm" class="space-y-4 md:space-y-6">
                        @csrf
                        <div>
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-600">Your name</label>
                            <input type="text" name="name" id="name"
                                class="bg-white border border-gray-200 text-gray-600 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required="">
                        </div>
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
                        <div>
                            <label for="password_confirmation"
                                class="block mb-2 text-sm font-medium text-gray-600">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="bg-white border border-gray-200 text-gray-600 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required="">
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign up</button>
                        <p class="text-sm font-light text-gray-500">
                            Already have an account? <a href="{{ route('login') }}"
                                class="font-medium text-blue-600 hover:underline">Sign in</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #signupSpinner {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #2563eb;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <div id="signupSpinner" class="flex">
        <div class="spinner"></div>
    </div>
</body>

</html>
