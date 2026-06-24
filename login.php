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
            --c-text-muted: #4B5563;
            --c-border: #E5E7EB;
            --f-heading: 'Outfit', sans-serif;
            --f-body: 'Plus Jakarta Sans', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--f-body);
            background: #FDF7F4;
            color: var(--c-text-main);
            min-height: 100vh;
            display: flex;
        }

        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Bagian Kiri - Form Login */
        .login-form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
            background: white;
            z-index: 10;
            box-shadow: 20px 0 40px rgba(0,0,0,0.05);
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--f-heading);
            font-size: 2rem;
            font-weight: 800;
            color: var(--c-secondary);
            margin-bottom: 2.5rem;
        }

        .logo span {
            color: var(--c-primary);
        }

        .logo-icon {
            background: var(--c-primary);
            color: white;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.5rem;
        }

        .login-box h1 {
            font-family: var(--f-heading);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: var(--c-text-main);
        }

        .login-box p {
            color: var(--c-text-muted);
            margin-bottom: 2.5rem;
            font-size: 1.05rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid var(--c-border);
            border-radius: 12px;
            font-family: var(--f-body);
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #F9FAFB;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--c-secondary);
            background: white;
            box-shadow: 0 0 0 4px rgba(43, 69, 223, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--c-primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: var(--f-heading);
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.2);
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: var(--c-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(255, 107, 53, 0.3);
        }

        .error-msg {
            background: #FEE2E2;
            color: #DC2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hint {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--c-text-muted);
            background: #F3F4F6;
            padding: 10px;
            border-radius: 8px;
            border: 1px dashed var(--c-border);
        }

        /* Bagian Kanan - Banner Visual */
        .login-banner-section {
            flex: 1.2;
            background: linear-gradient(135deg, rgba(43, 69, 223, 0.9), rgba(79, 70, 229, 0.9)), url('https://images.unsplash.com/photo-1495521821757-a1efb6729352?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem;
            border-radius: 24px;
            color: white;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            position: relative;
            z-index: 2;
        }

        .glass-card h2 {
            font-family: var(--f-heading);
            font-size: 2.8rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .glass-card p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.9;
        }

        .floating-element {
            position: absolute;
            font-size: 5rem;
            animation: float 6s ease-in-out infinite;
            opacity: 0.8;
        }

        .f1 { top: 10%; right: 15%; animation-delay: 0s; }
        .f2 { bottom: 15%; left: 10%; animation-delay: 2s; }
        .f3 { bottom: 30%; right: 20%; animation-delay: 4s; font-size: 3rem; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        @media (max-width: 900px) {
            .login-banner-section { display: none; }
            .login-form-section { padding: 2rem; }
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- Left Side: Form -->
    <div class="login-form-section">
        <div class="login-box">
            <div class="logo">
                <div class="logo-icon"><i class="fa-solid fa-utensils"></i></div>
                Recipe<span>Hub</span>
            </div>

            <h1>Selamat Datang</h1>
            <p>Silakan masuk ke Dapur Digital Anda untuk mengelola resep keluarga dan dunia.</p>

            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Nama Pengguna</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username Anda..." required autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan kata sandi rahasia..." required>
                </div>
                
                <button type="submit" name="login" class="btn-login">Masuk Dapur Sekarang <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></button>
            </form>

            <div class="hint">
                <strong>Petunjuk Final Project:</strong><br>
                Username: <code>admin</code><br>
                Password: <code>password</code>
            </div>
        </div>
    </div>

    <!-- Right Side: Visual Banner -->
    <div class="login-banner-section">
        <div class="floating-element f1">🍕</div>
        <div class="floating-element f2">🥗</div>
        <div class="floating-element f3">✨</div>
        
        <div class="glass-card">
            <h2 style="color: #FFD23F;">Masak Jadi Lebih Menyenangkan.</h2>
            <p>Jelajahi ribuan resep kelas dunia melalui API TheMealDB atau simpan resep warisan keluarga Anda sendiri dengan sistem pengelolaan yang mudah, rapi, dan cepat.</p>
        </div>
    </div>

</div>

</body>
</html>
