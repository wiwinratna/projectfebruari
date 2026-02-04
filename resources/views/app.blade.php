<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NOCIS</title>
    @vite(['resources/css/app.css', 'resources/js/main.tsx'])
</head>
<body class="bg-white">
    <div id="root"></div>
</body>
</html>
