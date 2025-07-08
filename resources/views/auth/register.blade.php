<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register - Data Padi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        body {
            background: linear-gradient(to right, #e6ffe6, #ccffcc);
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 25px;
            box-shadow: 0 8px 30px rgba(0, 128, 0, 0.2);
            animation: fadeInDown 0.9s;
        }

        .card-header {
            background-color: #f0fff0;
            border-radius: 25px 25px 0 0;
            text-align: center;
            font-weight: 600;
            font-size: 1.4rem;
            color: #449d44;
        }

        .form-control {
            border-radius: 1rem;
            padding: 0.75rem;
            transition: box-shadow 0.3s ease-in-out;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.4);
        }

        .btn-green {
            background-color: #5cb85c;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            border-radius: 1rem;
        }

        .btn-green:hover {
            background-color: #449d44;
            transform: scale(1.03);
        }

        .logo {
            width: 60px;
            margin-bottom: 10px;
        }

        .progress-bar {
            height: 4px;
            border-radius: 10px;
            background-color: #28a745;
            animation: slideProgress 2s linear infinite;
        }

        @keyframes slideProgress {
            0% {
                width: 0;
            }

            100% {
                width: 100%;
            }
        }

        .input-group-text {
            background: #e8f5e9;
            border: none;
            border-radius: 1rem 0 0 1rem;
        }

        .login-link {
            color: #5cb85c;
            font-weight: 500;
        }

        .login-link:hover {
            color: #449d44;
            text-decoration: underline;
        }

        .footer-padi {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 0.85rem;
            padding: 1rem;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-7 col-lg-5">
            <div class="card animate__animated animate__fadeInDown">
                <div class="card-header">
                    <div>🌾DAFTAR AKUN</div>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-user"></i></span></div>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-envelope"></i></span></div>
                                <input type="email" name="email" class="form-control" required
                                    value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-lock"></i></span></div>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-lock"></i></span></div>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-center my-3">
                            <small>Sudah punya akun? <a href="{{ route('login') }}" class="login-link">Login di
                                    sini</a></small>
                        </div>

                        <button type="submit" class="btn btn-green btn-block">
                            <span id="btnText">Daftar Sekarang</span>
                            <div id="loadingBar" class="progress mt-2 d-none">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"></div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-padi">
        &copy; {{ date('Y') }} Sistem Informasi Data Padi
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#registerForm').on('submit', function () {
            $('#btnText').text('Sedang memproses...');
            $('#loadingBar').removeClass('d-none');
        });
    </script>
</body>

</html>