<?php
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    echo "<script>alert('You have successfully logged out.');</script>";
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: url('assets/background.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .d-flex {
            position: relative;
            z-index: 1;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            color: #000;
        }

        /* White Glassmorphism Inputs */
        .glass-input {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #000;
        }

        .glass-input::placeholder {
            color: rgba(0, 0, 0, 0.6);
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.9) !important;
            color: #000 !important;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-check-input.glass-input {
            width: 1.2em;
            height: 1.2em;
        }

        .form-label {
            color: #000;
        }

        .text-light,
        .text-black,
        .text-dark {
            color: #000 !important;
        }

        /* Make checkbox white when unticked */
        .form-check-input {
            background-color: #fff;
            border: 1px solid #ccc;
            width: 1.2em;
            height: 1.2em;
        }

        /* When checked, use Bootstrap blue */
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>

<body class="bg-white">
    <div class="d-flex align-items-center min-vh-100 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div class="auth-logo mb-3">
                                    <a href="./index.php" class="text-decoration-none">
                                        <h3 class="fw-bold text-dark mb-0">Pusat Komputer Pertama Admin Dashboard</h3>
                                    </a>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Welcome Back!</h4>
                                <p class="text-dark">Sign in to your account to continue</p>
                            </div>
                            <div id="error-message" class="alert alert-danger text-center mb-3 d-none" role="alert"></div>
                            <form id="signin-form" onsubmit="return validateForm(event)">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control glass-input" id="username" name="username" placeholder="Enter your username" value="chasadmin">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control glass-input" id="password" name="password" placeholder="Enter your password">
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="remember-me">
                                    <label class="form-check-label" for="remember-me">Remember me</label>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg fw-medium" type="submit">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function validateForm(event) {
            event.preventDefault();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');

            const dummyUsername = 'chasadmin';
            const dummyPassword = 'gulugulu';

            if (!username) {
                errorMessage.textContent = 'Please enter a username';
                errorMessage.classList.remove('d-none');
                return false;
            } else if (username !== dummyUsername || password !== dummyPassword) {
                errorMessage.textContent = 'Invalid username or password';
                errorMessage.classList.remove('d-none');
                return false;
            } else {
                errorMessage.classList.add('d-none');
                alert('Login successfully!');
                setTimeout(() => {
                    window.location.href = './dashboard.php';
                }, 500);
                return true;
            }
        }
    </script>
</body>

</html>