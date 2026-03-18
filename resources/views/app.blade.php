<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $authUser = null;
        if (session('customer_authenticated') && session('customer_id')) {
            $user = \App\Models\User::with('profile')->find(session('customer_id'));
            if ($user) {
                $profilePhotoPath = optional($user->profile)->profile_photo;
                $profilePhotoUrl = null;

                if (!empty($profilePhotoPath)) {
                    $normalizedPath = ltrim((string) $profilePhotoPath, '/');
                    if (str_starts_with($normalizedPath, 'storage/')) {
                        $normalizedPath = substr($normalizedPath, strlen('storage/'));
                    }
                    if (!str_contains($normalizedPath, '/')) {
                        $normalizedPath = 'profile_photos/' . $normalizedPath;
                    }

                    $profilePhotoVersion = optional($user->profile?->updated_at)->timestamp;
                    $profilePhotoUrl = asset('storage/' . $normalizedPath)
                        . ($profilePhotoVersion ? '?v=' . $profilePhotoVersion : '');
                }

                $authUser = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_photo_url' => $profilePhotoUrl,
                ];
            }
        }
    @endphp
    <meta name="auth-user" content="{{ $authUser ? json_encode($authUser) : 'null' }}">
    <link rel="icon" href="{{ asset('images/Logo ARISE PNG.png') }}?v=2" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/Logo ARISE PNG.png') }}?v=2" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo ARISE PNG.png') }}?v=2">
    <title>ARISE</title>
    @vite(['resources/css/app.css', 'resources/js/main.tsx'])
</head>
<body class="bg-white">
    <div id="root"></div>
</body>
</html>
