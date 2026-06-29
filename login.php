<?php
session_start();

// Redirect jika sudah login
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Login Sederhana (Hardcoded untuk keperluan Final Project)
    // Username: admin | Password: password
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['login'] = true;
        $_SESSION['user'] = 'Administrator';
        header("Location: index.php");
        exit;
    } else {
        $error = 'Username atau Password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - RecipeHub</title>
    <!-- Premium Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --c-primary: #FF6B35;
            --c-primary-hover: #E85A25;
            --c-secondary: #2B45DF;
            --c-text-main: #111827;
            --c-text-muted: #6B7280;
            --c-border: #E5E7EB;
            --f-heading: 'Outfit', sans-serif;
            --f-body: 'Plus Jakarta Sans', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--f-body);
            background: #FFFFFF;
            color: var(--c-text-main);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Form Section */
        .login-form-section {
            flex: 0 0 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            background: #FFFFFF;
            z-index: 10;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
            animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--f-heading);
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--c-text-main);
            margin-bottom: 2.5rem;
        }

        .logo span {
            color: var(--c-primary);
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--c-primary), #FF8F60);
            color: white;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        }

        /* Typography */
        .login-box h1 {
            font-family: var(--f-heading);
            font-size: 2.4rem;
            margin-bottom: 0.5rem;
            color: var(--c-text-main);
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .login-box p {
            color: var(--c-text-muted);
            margin-bottom: 2.5rem;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #374151;
        }

        .input-with-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-with-icon i.icon-prefix {
            position: absolute;
            left: 1.2rem;
            color: #9CA3AF;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .input-with-icon .form-control {
            padding-left: 3.2rem;
        }

        .input-with-icon .form-control:focus ~ i.icon-prefix {
            color: var(--c-primary);
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            font-family: var(--f-body);
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #F8FAFC;
            color: var(--c-text-main);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--c-primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #FF6B35 0%, #E85A25 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: var(--f-heading);
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.25);
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255, 107, 53, 0.35);
        }

        .error-msg {
            background: #FEF2F2;
            color: #DC2626;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            border: 1px solid #FECACA;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .hint {
            margin-top: 2.5rem;
            font-size: 0.9rem;
            color: var(--c-text-muted);
            background: #F8FAFC;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .hint i {
            color: var(--c-primary);
            font-size: 1.2rem;
            margin-top: 2px;
        }

        /* Cute but professional subtle decor */
        .cute-decor {
            position: absolute;
            z-index: 1;
            opacity: 0.15;
            user-select: none;
            pointer-events: none;
        }

        .cd-1 { top: 15%; left: 10%; font-size: 3rem; transform: rotate(-15deg); }
        .cd-2 { bottom: 20%; right: 10%; font-size: 4rem; transform: rotate(15deg); }

        /* Right Banner Section */
        .login-banner-section {
            flex: 0 0 50%;
            /* Vibrant food spread image */
            background-image: url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            align-items: flex-end;
            padding: 4rem;
            position: relative;
        }

        /* Gradient Overlay for photo */
        .login-banner-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(17,24,39,0) 0%, rgba(17,24,39,0.8) 100%);
            z-index: 1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2.5rem;
            border-radius: 24px;
            color: white;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
            animation: slide-up 1s ease-out 0.3s both;
        }

        .glass-card h2 {
            font-family: var(--f-heading);
            font-size: 2.2rem;
            margin-bottom: 1rem;
            line-height: 1.2;
            font-weight: 800;
        }
        
        .glass-card p {
            font-size: 1.05rem;
            line-height: 1.6;
            color: rgba(255,255,255,0.85);
            margin: 0;
        }

        /* Author/Credit Badge */
        .author-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .author-badge img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .author-info h5 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .author-info span {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
        }

        @media (max-width: 900px) {
            .login-banner-section { display: none; }
            .login-form-section { flex: 1; padding: 2rem; }
            .login-box { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- Left Side: Form -->
    <div class="login-form-section">
        <!-- Subtle decorative elements -->
        <div class="cute-decor cd-1">🍜</div>
        <div class="cute-decor cd-2">🥗</div>
        
        <div class="login-box">
            <div class="logo">
                <div class="logo-icon"><i class="fa-solid fa-utensils"></i></div>
                Recipe<span>Hub</span>
            </div>

            <h1>Selamat Datang!</h1>
            <p>Silakan masuk ke panel pengelola resep untuk menemukan dan mengatur koleksi hidangan favorit Anda.</p>

            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Nama Pengguna</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-user icon-prefix"></i>
                        <input type="text" id="username" name="username" class="form-control" placeholder="admin" required autocomplete="off">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock icon-prefix"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <button type="submit" name="login" class="btn-login">
                    Masuk Sekarang <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="hint">
                <i class="fa-solid fa-lightbulb"></i>
                <div>
                    <span style="display:block; font-weight: 600; color: var(--c-text-main); margin-bottom: 2px;">Catatan Kredensial</span>
                    Untuk demo ini, gunakan Username: <strong>admin</strong> dan Password: <strong>password</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Visual Banner -->
    <div class="login-banner-section">
        <div class="glass-card">
            <h2>Eksplorasi Dunia<br><span style="color: #FFD23F;">Kuliner Global.</span></h2>
            <p>Akses ribuan resep autentik dari seluruh belahan dunia. Simpan, kelola, dan ciptakan keajaiban di dapur Anda dengan panduan langkah demi langkah.</p>
            
            <div class="author-badge">
                <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?q=80&w=200&auto=format&fit=crop" alt="Chef">
                <div class="author-info">
                    <h5>Chef's Recommendation</h5>
                    <span>Bergabunglah dengan ribuan penikmat kuliner lainnya</span>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
