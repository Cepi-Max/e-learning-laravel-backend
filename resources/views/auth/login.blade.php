<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Data Padi</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        :root {
            --primary: #6baf54;
            --primary-dark: #4c8d3d;
            --bg: #f5f9f3;
            --text: #212529;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .login-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            padding: 40px 32px;
            width: 100%;
            max-width: 400px;
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease-out;
        }

        .login-wrapper::before {
            content: "";
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 150px;
            height: 150px;
            background: url('https://img.icons8.com/ios-filled/100/6baf54/rice-plant.png') no-repeat center;
            background-size: 80px;
            opacity: 0.05;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h4 {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-dark);
        }

        .form-control {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 0.2rem rgba(107, 175, 84, 0.25);
        }

        .input-group-text {
            background-color: #eaf4e3;
            border: none;
            border-radius: 10px 0 0 10px;
            color: var(--primary-dark);
        }

        .btn-padi {
            background-color: var(--primary);
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            transition: background 0.3s ease, transform 0.2s;
        }

        .btn-padi:hover {
            background-color: var(--primary-dark);
            transform: scale(1.02);
        }

        .text-register {
            font-size: 0.9rem;
            text-align: center;
            margin-top: 15px;
        }

        .text-register a {
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }

        .text-register a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-header">
            <h4>🌾Data Padi</h4>
            <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Progress Bar -->
        <div id="progress-container" class="mb-3" style="display: none;">
            <div class="progress" role="progressbar" aria-label="Loading..." aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"
                    id="progress-bar">
                    Loading...
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" id="login-form">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email"
                        required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" name="password" id="password"
                        placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-padi" id="login-button">Masuk</button>
            </div>
        </form>

        <div class="text-register">
            Belum punya akun? <a href="{{ route('register.show') }}">Daftar di sini</a>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('login-form');
            const loginBtn = document.getElementById('login-button');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');

            loginForm.addEventListener('submit', function () {
                progressContainer.style.display = 'block';
                loginBtn.disabled = true;
                loginBtn.innerHTML = 'Memproses...';

                let width = 0;
                const interval = setInterval(() => {
                    if (width >= 100) {
                        clearInterval(interval);
                    } else {
                        width += 10;
                        progressBar.style.width = width + '%';
                        progressBar.setAttribute('aria-valuenow', width);
                    }
                }, 100);
            });
        });
    </script>
</body>

</html>