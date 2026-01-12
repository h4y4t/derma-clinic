<?php
session_start();
include 'db_connection.php'; // Adjust if your DB file is named differently

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Try to find user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows === 1) {
        $user = $userResult->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = 'user';
            header("Location:main.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        // Try to find doctor
        $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $doctorResult = $stmt->get_result();

        if ($doctorResult->num_rows === 1) {
            $doctor = $doctorResult->fetch_assoc();
            if (password_verify($password, $doctor['password'])) {
                $_SESSION['user_id'] = $doctor['id'];
                $_SESSION['email'] = $doctor['email'];
                $_SESSION['user_type'] = 'doctor';
                header("Location: main.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Account not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Derma Clinic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Cairo', sans-serif;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

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
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="main.php">Main Page</a>
            <a href="doctors.php">Doctors</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="about.html">About Us</a>
            <a href="login.php" class="active">Login</a>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </div>
</div>

<!-- Login Form -->

<main>
    <div class="form-container">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="server-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="eye-icon" onmouseover="togglePassword('password', true)" onmouseout="togglePassword('password', false)">👁</span>
            </div>
            <button type="submit">Login</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Don't have an account?
            <a href="signup.php" style="color: #6a0dad; text-decoration: underline;">Sign up</a>
        </p>
    </div>
</main>

<!-- Footer -->
<footer style="margin-top: auto">
    <p>© 2025 Derma Clinic. All rights reserved.</p>
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

<!-- Script -->
<script>
    function togglePassword(id, show) {
        const field = document.getElementById(id);
        field.type = show ? 'text' : 'password';
    }
</script>

</body>
</html>
