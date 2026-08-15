<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard {{ Auth::user()->role_label }} - Segera Hadir</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #f8f6f1;
            --navy-color: #23293b;
            --cream-bg: #f7f3eb;
            --cream-border: #e8e2d5;
            --text-dark: #1e2538;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .coming-card {
            background: #ffffff;
            border: 1px solid var(--cream-border);
            border-radius: 24px;
            padding: 48px 40px;
            max-width: 540px;
            width: 100%;
            text-align: center;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            position: relative;
        }

        .user-top-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-logout {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: #dc2626;
            color: #ffffff;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--cream-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .icon-circle svg {
            width: 40px;
            height: 40px;
            color: var(--navy-color);
        }

        .role-pill {
            display: inline-block;
            background-color: var(--navy-color);
            color: #ffffff;
            font-size: 0.825rem;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .coming-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .coming-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 440px;
        }

        .btn-action-group {
            display: flex;
            gap: 12px;
            margin-top: 10px;
            width: 100%;
        }

        .btn-primary-action {
            flex: 1;
            background-color: var(--navy-color);
            color: #ffffff;
            padding: 13px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary-action:hover {
            background-color: #171c2b;
            transform: translateY(-1px);
        }

        .btn-secondary-action {
            background-color: var(--cream-bg);
            border: 1px solid var(--cream-border);
            color: var(--text-dark);
            padding: 13px 20px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-secondary-action:hover {
            background-color: #efe7d9;
        }
    </style>
</head>
<body>

    <div class="coming-card">
        <!-- Top Logout Option -->
        <div class="user-top-badge">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>

        <!-- Role Icon -->
        <div class="icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>

        <span class="role-pill">{{ Auth::user()->role_label }}</span>

        <h1 class="coming-title">Halaman Role {{ Auth::user()->role_label }} Segera Hadir</h1>

        <p class="coming-subtitle">
            Halo <strong>{{ Auth::user()->name }}</strong>, modul antarmuka khusus untuk role <strong>{{ Auth::user()->role_label }}</strong> saat ini sedang dalam tahap rancangan dan pengembangan.
        </p>

        <div class="btn-action-group">
            <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="btn-primary-action" style="width: 100%; border: none; cursor: pointer;">Keluar / Switch Akun</button>
            </form>
        </div>
    </div>

</body>
</html>
