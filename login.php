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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --c-primary: #FF5A00;
            --c-primary-dark: #E04D00;
            --c-primary-light: #FF8542;
            --c-white: #FFFFFF;
            --c-text-main: #1F2937;
            --c-text-muted: #6B7280;
            --c-bg: #F9FAFB;
            --f-heading: 'Outfit', sans-serif;
            --f-body: 'Plus Jakarta Sans', sans-serif;
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--f-body);
            background-color: var(--c-bg);
            color: var(--c-text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden; /* Prevent horizontal scroll, allow vertical */
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Panel - The Form */
        .login-sidebar {
            width: 50%;
            background: var(--c-white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem 6%;
            position: relative;
            z-index: 10;
            box-shadow: 20px 0 50px rgba(0,0,0,0.05);
        }

        .login-content-inner {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        /* Right Panel - The Image */
        .login-image {
            width: 50%;
            position: relative;
            /* Beautiful colorful food spread image */
            background-image: url('https://img.magnific.com/foto-gratis/tampak-atas-meja-penuh-makanan_23-2149209251.jpg?semt=ais_hybrid&w=740&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 10%;
        }

        /* Gentle brand overlay to make text readable and match theme */
        .login-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 90, 0, 0.45) 0%, rgba(17, 24, 39, 0.2) 50%, rgba(43, 69, 223, 0.45) 100%);
            z-index: 1;
        }

        .image-content {
            position: relative;
            z-index: 2;
            color: white;
            animation: slideUp 1s ease-out forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .image-content h2 {
            font-family: var(--f-heading);
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
            text-shadow: 0 4px 10px rgba(0,0,0,0.4);
        }

        .image-content p {
            font-size: 1.2rem;
            line-height: 1.6;
            text-shadow: 0 2px 5px rgba(0,0,0,0.4);
            max-width: 600px;
            opacity: 0.95;
        }

        /* Decorative Badge on Image */
        .image-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 100px;
            font-family: var(--f-body);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-shadow: none;
        }

        /* Branding */
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--f-heading);
            font-size: 2rem;
            font-weight: 800;
            color: var(--c-text-main);
            margin-bottom: 3rem;
            text-decoration: none;
        }

        .brand span {
            color: var(--c-primary);
        }

        .brand-icon {
            background: var(--c-primary);
            color: white;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.2rem;
            box-shadow: 0 8px 15px rgba(255, 90, 0, 0.3);
        }

        /* Form Styling */
        .login-header {
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-family: var(--f-heading);
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--c-text-main);
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: var(--c-text-muted);
            font-size: 1rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group i {
            position: absolute;
            left: 1.2rem;
            color: #9CA3AF;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control {
            width: 100%;
            padding: 14px 14px 14px 3.2rem;
            background: #F9FAFB;
            border: 1px solid #D1D5DB;
            border-radius: 12px;
            font-family: var(--f-body);
            font-size: 0.95rem;
            color: var(--c-text-main);
            transition: var(--transition);
        }

        .form-control::placeholder {
            color: #9CA3AF;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--c-primary);
            background: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(255, 90, 0, 0.1);
        }

        .input-group:focus-within i {
            color: var(--c-primary);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--c-primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: var(--f-heading);
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 8px 20px rgba(255, 90, 0, 0.3);
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background: var(--c-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255, 90, 0, 0.4);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }

        .error-alert {
            background: #FEF2F2;
            color: #DC2626;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            border: 1px solid #FECACA;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .demo-credentials {
            margin-top: 2rem;
            padding: 14px;
            background: #FFF7ED;
            border: 1px solid #FFEDD5;
            border-radius: 10px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.85rem;
            color: #9A3412;
        }

        .demo-credentials i {
            color: var(--c-primary);
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .demo-credentials strong {
            color: #7C2D12;
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .login-sidebar { width: 50%; padding: 2rem; }
            .login-image { width: 50%; padding: 3rem; }
            .image-content h2 { font-size: 2.5rem; }
        }

        @media (max-width: 768px) {
            .login-image { display: none; }
            .login-sidebar { width: 100%; padding: 2rem; align-items: center; }
            .login-content-inner { width: 100%; max-width: 400px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- Left Panel: Form -->
    <div class="login-sidebar">
        <div class="login-content-inner">
            <a href="#" class="brand">
                <div class="brand-icon"><i class="fa-solid fa-utensils"></i></div>
                Recipe<span>Hub</span>
            </a>

            <div class="login-header">
                <h1>Mulai Memasak!</h1>
                <p>Masuk untuk menemukan resep-resep istimewa dan bagikan kreasi lezat Anda.</p>
            </div>

            <?php if ($error): ?>
                <div class="error-alert">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Nama Pengguna</label>
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="username" name="username" class="form-control" placeholder="admin" required autocomplete="off">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <button type="submit" name="login" class="btn-submit">
                    Masuk ke Dashboard <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="demo-credentials">
                <i class="fa-solid fa-circle-info"></i>
                <div>
                    <div style="margin-bottom: 2px;">Data Login (Demo):</div>
                    Username: <strong>admin</strong> &nbsp;|&nbsp; Password: <strong>password</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel: Image -->
    <div class="login-image">
        <div class="image-content">
            <div class="image-badge">
                <i class="fa-solid fa-star" style="color: #FFD23F;"></i> Resep Terbaik dari Seluruh Dunia
            </div>
            <h2>Dunia Rasa dalam Satu Sentuhan.</h2>
            <p>Jelajahi ribuan kombinasi rasa, temukan inspirasi menu harian, dan hadirkan kebahagiaan di meja makan Anda setiap hari bersama RecipeHub.</p>
        </div>
    </div>
</div>

</body>
</html>
