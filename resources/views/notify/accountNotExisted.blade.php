<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen" style="font-family: bahnschrift">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h1 class="text-2xl font-bold text-red-600 mb-4">This account does not exist</h1>
        <p class="text-gray-700">
            Please double-check the information or contact the administrator.
            <a href="{{ url('/') }}" class="text-gray-700 hover:text-black mt-4 inline-block underline">
                Go back
            </a>
        </p>
    </div>
</body>
</html>
