<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            height: 100vh;
            background: url("{{ asset('images/login-bg.jpeg') }}") no-repeat center center;
            background-size: cover;

            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 80px;
        }

        /* ================= LOGO ================= */
        .logo-wrapper {
            width: 400px;
            height: 400px;

            background: transparent;

            display: flex;
            align-items: center;
            justify-content: center;

            position: relative;
            top: -100px;

            transform: translateX(60px);
        }

        .logo-wrapper img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* ================= LOGIN BOX ================= */
        .login-container {
            width: 380px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 8px;

            /* geser ke kiri */
            transform: translateX(-60px);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-bottom: 1px solid #aaa;
            outline: none;
            background: transparent;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background: #222;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            letter-spacing: 1px;
        }

        .login-container button:hover {
            background: #000;
        }

        .forgot {
            text-align: right;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
                justify-content: center;
                padding: 20px;
            }

            .logo-wrapper,
            .login-container {
                transform: none;
                position: static;
            }

            .logo-wrapper {
                width: 160px;
                height: 160px;
                margin-bottom: 30px;
            }

            .login-container {
                width: 100%;
                max-width: 380px;
            }
        }
    </style>
</head>
<body>

    <!-- LOGO TANPA BG -->
    <div class="logo-wrapper">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
    </div>

    <!-- LOGIN FORM -->
    <div class="login-container">
        <h2>LOGIN</h2>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <div class="forgot">
                <a href="#" style="text-decoration:none;color:#555;">Forgot Password?</a>
            </div>

            <button type="submit">LOGIN</button>
        </form>
    </div>

</body>
</html>
