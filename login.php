<?php
require 'koneksi.php';
require 'jwt.php';

$secret = 'budiman';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!isset($email) || !isset($password)) {
        exit(400);
    }
    $res = mysqli_query($conn, "SELECT id_user, password FROM users WHERE email='$email'");
    $data = mysqli_fetch_assoc($res);

    if (!$res) {
        exit(400);
    }
    if (password_verify($password, $data['password']) === FALSE) {
        exit(400);
    }

    [$token, $options] = sign_jwt($data['id_user']);

    setcookie('token', $token, $options);
    exit(200);
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TripKuy - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media (max-width: 768px) {
            .login-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .login-actions {
                width: 100%;
                justify-content: center;
            }
            
            .login-form-container {
                padding: 2rem 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .form-title {
                font-size: 2rem;
            }
            
            .login-button {
                padding: 0.875rem;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body class="bg-white min-h-screen">
    <!-- Header -->
    <header class="flex flex-col sm:flex-row items-center justify-between px-4 sm:px-6 lg:px-8 py-4 sm:py-6 login-header">
        <div class="flex items-center gap-2 mb-4 sm:mb-0">
            <div class="w-8 h-8 bg-black flex items-center justify-center rounded">
                <div class="text-white text-xl font-bold">≡</div>
            </div>
            <span class="text-2xl font-bold">TripKuy</span>
        </div>
        <div class="flex items-center gap-3 login-actions">
            <a href="./register.php"
                class="px-4 sm:px-6 py-2 bg-black text-white text-sm rounded-md hover:bg-gray-800 transition-colors whitespace-nowrap">
                Register
            </a>
            <a href="./login.php"
                class="px-4 sm:px-6 py-2 border-2 border-black text-black text-sm rounded-md hover:bg-gray-50 transition-colors whitespace-nowrap">
                Login
            </a>
        </div>
    </header>

    <!-- Login Form -->
    <div class="flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8 login-form-container">
        <div class="w-full max-w-md">
            <h1 class="text-3xl sm:text-4xl font-bold mb-3 form-title text-center">Sign in</h1>
            <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base text-center">
                If you don't have an account register<br />
                You can
                <a href="./register.php"
                    class="text-blue-600 font-semibold hover:underline">Register here!</a>
            </p>

            <form id="loginForm" class="space-y-4 sm:space-y-6">
                <!-- Username Input -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">👤</span>
                        <input
                            type="text"
                            name="_username"
                            id="username"
                            placeholder="Enter your username"
                            class="w-full pl-10 pr-4 py-3 border-b-2 border-gray-300 focus:border-blue-600 outline-none transition-colors bg-transparent text-sm sm:text-base"
                            required />
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">🔒</span>
                        <input
                            type="password"
                            name="_password"
                            id="password"
                            placeholder="Enter your password"
                            class="w-full pl-10 pr-12 py-3 border-b-2 border-gray-300 focus:border-blue-600 outline-none transition-colors bg-transparent text-sm sm:text-base"
                            required />
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg
                                id="eyeIcon"
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between text-sm gap-2 sm:gap-0">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            id="rememberMe"
                            class="w-4 h-4 rounded border-gray-300 accent-blue-600" />
                        <span class="text-gray-700">Remember me</span>
                    </label>
                    <a href="#"
                        class="text-gray-600 hover:text-blue-600 sm:text-right">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="w-full py-3 sm:py-4 bg-blue-600 text-white rounded-full font-semibold text-base sm:text-lg hover:bg-blue-700 transition-colors shadow-md login-button">
                    Login
                </button>

                <!-- Register Link -->
                <div class="text-center pt-2">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="./register.php"
                            class="text-blue-600 font-semibold hover:underline">Register here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        togglePassword.addEventListener("click", function() {
            const type =
                passwordInput.getAttribute("type") === "password" ?
                "text" :
                "password";
            passwordInput.setAttribute("type", type);

            if (type === "text") {
                eyeIcon.innerHTML =
                    '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                eyeIcon.innerHTML =
                    '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        });

        // Handle form submission
        const loginForm = document.getElementById("loginForm");
        loginForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            const rememberMe = document.getElementById("rememberMe").checked;

            console.log("Login attempt:", {
                username,
                password,
                rememberMe
            });

            const formData = new FormData(e.target);
            try {
                const res = await fetch('./login.php', {
                    method: "POST",
                    body: formData
                });

                if (res.status == 200) {
                    window.location.href = "dashboard.php";
                } else {
                    const errorText = await res.text();
                    console.error("Login failed:", errorText);
                    alert("Login gagal. Periksa username dan password Anda.");
                }
            } catch (error) {
                console.error("Error during login:", error);
                alert("Terjadi kesalahan. Silakan coba lagi.");
            }
        });
    </script>
</body>
</html>
