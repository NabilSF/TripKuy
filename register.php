<?php
require 'koneksi.php';
require 'jwt.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    echo $password;
    $password = $_POST['password'];


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $res = mysqli_query($conn, "INSERT INTO users(nama, email, password, role, no_telepon) VALUES ('$username', '$email', '$hashed_password', 'user', '$phone')");

    if ($res) {
        [$token, $options] = sign_jwt($conn->insert_id);
        setcookie('token', $token, $options);
        exit(200);
    }
    exit(400);
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TripKuy - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media (max-width: 768px) {
            .register-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .register-actions {
                width: 100%;
                justify-content: center;
            }
            
            .hotel-image {
                height: 300px;
                object-fit: cover;
            }
        }
        
        @media (max-width: 640px) {
            .register-form-container {
                padding: 1rem;
            }
            
            .form-title {
                font-size: 2rem;
            }
            
            .register-button {
                padding: 0.875rem;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body class="bg-white">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Side - Register Form -->
        <div class="w-full lg:w-1/2 flex flex-col order-2 lg:order-1">
            <!-- Header -->
            <header class="flex flex-col sm:flex-row items-center justify-between px-4 sm:px-6 lg:px-8 py-4 sm:py-6 register-header">
                <div class="flex items-center gap-2 mb-4 sm:mb-0">
                    <div class="w-8 h-8 bg-black flex items-center justify-center rounded">
                        <div class="text-white text-xl font-bold">≡</div>
                    </div>
                    <span class="text-2xl font-bold">TripKuy</span>
                </div>
                <div class="flex items-center gap-3 register-actions">
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

            <!-- Register Form -->
            <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8 lg:py-0 register-form-container">
                <div class="w-full max-w-md">
                    <h1 class="text-3xl sm:text-4xl font-bold mb-3 form-title">Sign up</h1>
                    <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base">
                        If you already have an account<br />
                        You can
                        <a href="./login.php"
                            class="text-blue-600 font-semibold hover:underline">Login here!</a>
                    </p>

                    <form id="registerForm" method="post" class="space-y-4 sm:space-y-6">
                        <!-- Nama Lengkap Input -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">👤</span>
                                <input
                                    type="text"
                                    name="fullname"
                                    id="fullname"
                                    placeholder="Enter your full name"
                                    class="w-full pl-10 pr-4 py-3 border-b-2 border-gray-300 focus:border-blue-600 outline-none transition-colors bg-transparent text-sm sm:text-base"
                                    required />
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Email</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">✉</span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="Enter your email address"
                                    class="w-full pl-10 pr-4 py-3 border-b-2 border-gray-300 focus:border-blue-600 outline-none transition-colors bg-transparent text-sm sm:text-base"
                                    required />
                            </div>
                        </div>

                        <!-- Nomor Telepon Input -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Nomor Telepon</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">📱</span>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    placeholder="Enter your phone number"
                                    class="w-full pl-10 pr-4 py-3 border-b-2 border-gray-300 focus:border-blue-600 outline-none transition-colors bg-transparent text-sm sm:text-base"
                                    required
                                    pattern="[0-9]+" />
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">🔒</span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Enter your Password"
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

                        <!-- Confirm Password Input -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">🔒</span>
                                <input
                                    type="password"
                                    id="confirmPassword"
                                    name="confirm_password"
                                    placeholder="Confirm your Password"
                                    class="w-full pl-10 pr-12 py-3 border-b-2 border-gray-300 focus:border-blue-600 outline-none transition-colors bg-transparent text-sm sm:text-base"
                                    required />
                                <button
                                    type="button"
                                    id="toggleConfirmPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg
                                        id="eyeIconConfirm"
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

                        <!-- Register Button -->
                        <button
                            type="submit"
                            class="w-full py-3 sm:py-4 bg-blue-600 text-white rounded-full font-semibold text-base sm:text-lg hover:bg-blue-700 transition-colors shadow-md register-button">
                            Register
                        </button>

                        <!-- Login Link -->
                        <div class="text-center pt-2">
                            <p class="text-sm text-gray-600">
                                Already have an account?
                                <a href="./login.php"
                                    class="text-blue-600 font-semibold hover:underline">Login here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side - Hotel Image -->
        <div class="w-full lg:w-1/2 bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center relative order-1 lg:order-2">
            <!-- Hotel Image with Overlay -->
            <div class="absolute inset-0 bg-black opacity-40"></div>
            
            <!-- Hotel Image -->
            <div class="relative w-full h-full">
                <!-- Placeholder Hotel Image - You can replace this with actual hotel image -->
                <div class="w-full h-full hotel-image" style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center center / cover;">
                    <!-- Content overlay -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-6 sm:p-8 lg:p-12 text-center text-white">
                        <div class="mb-6 sm:mb-8 lg:mb-12">
                            <div class="text-5xl sm:text-6xl mb-4"></div>
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4">
                                Discover Amazing Hotels
                            </h2>
                            <p class="text-lg sm:text-xl text-blue-100">
                                Join TripKuy and find the perfect stay for your next adventure
                            </p>
                        </div>
                        
                        <!-- Hotel Features -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6 max-w-lg">
                            <div class="flex flex-col items-center">
                                <div class="text-2xl mb-2"></div>
                                <span class="text-sm sm:text-base">Luxury Stays</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="text-2xl mb-2"></div>
                                <span class="text-sm sm:text-base">Best Prices</span>
                            </div>
                            <div class="flex flex-col items-center sm:col-span-1 col-span-2">
                                <div class="text-2xl mb-2"></div>
                                <span class="text-sm sm:text-base">Easy Booking</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        // Toggle confirm password visibility
        const toggleConfirmPassword = document.getElementById(
            "toggleConfirmPassword"
        );
        const confirmPasswordInput =
            document.getElementById("confirmPassword");
        const eyeIconConfirm = document.getElementById("eyeIconConfirm");

        toggleConfirmPassword.addEventListener("click", function() {
            const type =
                confirmPasswordInput.getAttribute("type") === "password" ?
                "text" :
                "password";
            confirmPasswordInput.setAttribute("type", type);

            if (type === "text") {
                eyeIconConfirm.innerHTML =
                    '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                eyeIconConfirm.innerHTML =
                    '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        });

        // Handle form submission
        const registerForm = document.getElementById("registerForm");
        registerForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            const fullname = document.getElementById("fullname").value;
            const email = document.getElementById("email").value;
            const phone = document.getElementById("phone").value;
            const password = document.getElementById("password").value;
            const confirmPassword =
                document.getElementById("confirmPassword").value;

            // Validasi password match
            if (password !== confirmPassword) {
                alert("Password dan Confirm Password tidak sama!");
                return;
            }

            console.log("Register attempt:", {
                fullname,
                email,
                phone,
                password,
            });

            const formData = new FormData(e.target);
            try {
                const res = await fetch("./register.php", {
                    method: "POST",
                    body: formData,
                });

                if (res.status == 200) {
                    const result = await res.json();
                    if (result.success) {
                        alert("Registrasi berhasil! Silakan login.");
                        window.location.href = "login.php";
                    } else {
                        alert("Registrasi gagal: " + result.message);
                    }
                } else {
                    const errorText = await res.text();
                    console.error("Register failed:", errorText);
                    alert("Registrasi gagal. Silakan coba lagi.");
                }
            } catch (error) {
                console.error("Error during registration:", error);
                alert("Terjadi kesalahan. Silakan coba lagi.");
            }
        });
    </script>
</body>
</html>
