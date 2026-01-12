<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Appointment Success - Derma Clinic</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;

        }
        .success-section {
            flex: 1;
            padding: 60px 20px;
            text-align: center;
        }
        .success-box {
            background-color: #f8e8f8;
            border: 2px solid #d98af1;

            border-radius: 15px;
            padding: 40px;
            display: inline-block;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in-out forwards;
        }
        .success-box h1 {
            color: #6a0dad;
            margin-bottom: 20px;
        }
        .success-box p {
            font-size: 18px;
            color: #333;
        }
        .navbar {
            display: flex;
            align-items: center;
            padding: 15px 30px;
            background: linear-gradient(135deg, #e6ccff, #d8b4e2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            gap: 40px;
            flex-wrap: wrap;
        }
        .logo {
            font-family: 'Caveat', cursive;
            font-size: 32px;
            color: #8e44ad;
            margin-bottom: 5px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }
        .motto {
            font-size: 14px;
            color: #888;
        }
        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #000000;
            font-weight: bold;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        .btn-primary {
            display: inline-block;
            padding: 14px 36px;
            background: linear-gradient(135deg, #8e44ad, #6a0dad);
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            border-radius: 30px;
            text-decoration: none;
            box-shadow: 0 6px 16px rgba(110, 43, 171, 0.55);
            transition: background 0.3s ease, transform 0.2s ease;
            user-select: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #b262ec, #be79d9);
            transform: scale(1.05);
        }
        input[type="submit"] {
            margin-top: 25px;
            padding: 14px 36px;
            background: linear-gradient(135deg, #8e44ad, #6a0dad);
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 30px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 6px 16px rgba(110, 43, 171, 0.55);
            transition: background 0.3s ease, transform 0.2s ease;
            user-select: none;
            display: block;
            margin-left: auto;
        }
        input[type="submit"]:hover {
            background: linear-gradient(135deg, #6a0dad, #8e44ad);
            transform: scale(1.05);
        }
        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            color: #4a235a;
            font-size: 14px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Profile Icon Styles */
        .profile-icon {
            position: absolute;
            top: 25px;
            right: 30px;
            width: 40px;
            height: 40px;
            cursor: pointer;
            border-radius: 50%; /* هذه تجعل الصورة دائرية */
            object-fit: cover;   /* لضمان ظهور الصورة بشكل جميل داخل الدائرة */
        }
    </style>
</head>
<body>
<!-- Navbar -->
<div class="navbar">
    <div class="header-left">
        <div class="logo">Derma Clinic</div>
        <div class="motto">"Your Skin, Our Priority"</div>
    </div>
    <div class="nav-links">
        <a href="main.php" >Main Page</a>
        <a href="doctors.php">Doctors</a>
        <a href="#about">About Us</a>
        <a href="my_appointment.php"class="active">My Appointment</a>
    </div>
    <!-- Profile Icon (Top Right Corner) -->
    <a href="profile.php">
        <img src="images/profile.jpg" class="profile-icon" alt="Profile Icon">
    </a>
</div>

<!-- Success Message -->
<div class="success-section">
    <div class="success-box">
        <h1>Appointment booked successfully! 🎉</h1>
        <p>Thank you for choosing our clinic. You can review your appointment details below:</p>
        <a href="my_appointment.php" class="btn-primary">My Appointment</a>    </div>
</div>

<!-- Footer -->
<footer>
    &copy; <?= date('Y') ?> Derma Clinic. All rights reserved.
</footer>

</body>
</html>