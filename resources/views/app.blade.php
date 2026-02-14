<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $authUser = null;
        if (session('customer_authenticated') && session('customer_id')) {
            $user = \App\Models\User::find(session('customer_id'));
            if ($user) {
                $authUser = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }
        }
    @endphp
    <meta name="auth-user" content="{{ $authUser ? json_encode($authUser) : 'null' }}">
    <title>ARISE</title>
    @vite(['resources/css/app.css', 'resources/js/main.tsx'])
</head>
<body class="bg-white">
    <div id="root"></div>
</body>
</html>
