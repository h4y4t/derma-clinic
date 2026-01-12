<?php
session_start();
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Derma Clinic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            color: #6a0dad;
        }

        .input-group {
            position: relative;
            margin: 15px 0;
        }

        .form-container input {
            width: 100%;
            padding: 12px 40px 12px 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .eye-icon {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #666;
        }

        .error-message, .server-error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-container button {
            width: 100%;
            background-color: #a16bdc;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .form-container button:hover {
            background-color: #7e57c2;
        }

        .form-container a:hover {
            color: #4b007d;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="navbar">
    <div class="header-left">
        <div class="logo">Derma Clinic</div>
        <div class="motto">"Your Skin, Our Priority"</div>
    </div>
    <div class="nav-links">
        <a href="main.php">Main Page</a>
        <a href="doctors.php">Doctors</a>
        <a href="login.php.php">Login</a>
        <a href="about.html">About Us</a>
    </div>
</div>

<!-- Form -->
<div class="form-container">
    <h2>Sign Up</h2>

    <!-- Server-side error -->
    <!-- Server-side error -->
    <?php if (!empty($error)): ?>
        <div class="server-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>


    <form action="signup_process.php" method="POST" onsubmit="return validateForm()">
        <div class="input-group">
            <input type="text" name="name" required placeholder="Full Name">
        </div>
        <div class="input-group">
            <input type="email" name="email" required placeholder="Email">
        </div>
        <div class="input-group">
            <input type="password" name="password" id="password" required placeholder="Password">
            <span class="eye-icon" onmouseover="togglePassword('password', true)" onmouseout="togglePassword('password', false)">👁</span>
        </div>
        <div class="input-group">
            <input type="password" name="confirm" id="confirmPassword" placeholder="Confirm Password" required oninput="checkMatch()">
            <span class="eye-icon" onmouseover="togglePassword('confirmPassword', true)" onmouseout="togglePassword('confirmPassword', false)">👁</span>
            <div id="matchError" class="error-message" style="display:none;">Passwords do not match</div>
        </div>

        <button type="submit">Create Account</button>
    </form>

    <!-- Login Link -->
    <p style="text-align: center; margin-top: 15px; font-size: 14px;">
        Already have an account?
        <a href="login.php" style="color: #6a0dad; text-decoration: underline;">Log in</a>
    </p>
</div>

<!-- Footer -->
<footer>
    <p>© 2025 Derma Clinic. All rights reserved.</p>
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

<!-- JS -->

<script>
    function togglePassword(id, show) {
        const field = document.getElementById(id);
        field.type = show ? 'text' : 'password';
    }

    function checkMatch() {
        const pw = document.getElementById("password").value;
        const cpw = document.getElementById("confirmPassword").value;
        const error = document.getElementById("matchError");
        if (cpw.length > 0 && pw !== cpw) {
            error.style.display = "block";
        } else {
            error.style.display = "none";
        }
    }

    function validateForm() {
        const pw = document.getElementById("password").value;
        const cpw = document.getElementById("confirmPassword").value;
        if (pw !== cpw) {
            document.getElementById("matchError").style.display = "block";
            return false;
        }
        return true;
    }

</script>

</body>
</html>
