<!DOCTYPE html>
<html>
<head>
    <title>Employee Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        @yield('content')
    </div>
</body>
</html>
