<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Absensi Guru')</title>
</head>
<body>
    <div style="max-width: 800px; margin: 30px auto; font-family: sans-serif;">
        @if(session('success'))
            <div style="background:#d4edda; padding:10px; margin-bottom:15px; border-radius:4px;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>