<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-lg w-96">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Login</h1>
        <form method="POST" action="/login">
            @csrf
            @if($errors->any())<span
                class="self-center text-1xl text-red-600 font-semibold whitespace-nowrap dark:text-white">{{$errors->first()}}</span>@endif
            <div class="mb-4">
                <label for="email" class="block text-gray-600 text-sm font-medium mb-2">Email</label>
                <input type="email" id="email" name="email"
                    class="w-full border rounded-md py-2 px-3 text-gray-700 focus:outline-none focus:border-blue-400"
                    placeholder="Enter Email" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-600 text-sm font-medium mb-2">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full border rounded-md py-2 px-3 text-gray-700 focus:outline-none focus:border-blue-400"
                    placeholder="Enter Password" required>
            </div>
            <button type="submit"
                class="w-full bg-blue-500 text-white font-semibold rounded-md py-2 px-4 hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">Login</button>

        </form>
    </div>
</body>

</html>