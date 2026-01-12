<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
$user_type = $_SESSION['user_type'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Derma Clinic</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: linear-gradient(135deg, #e6ccff, #d8b4e2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header-left .logo {
            font-family: 'Caveat', cursive;
            font-size: 32px;
            color: #8e44ad;
        }
        .header-left .motto {
            font-size: 14px;
            color: #888;
        }
        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #6a0dad;
            font-weight: bold;
        }
        .nav-links a.active, .nav-links a:hover {
            text-decoration: underline;
        }
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="header-left">
        <div class="logo">Derma Clinic</div>
        <div class="motto">"Your Skin, Our Priority"</div>
    </div>

    <div class="nav-links">
        <?php if ($user_type === 'doctor'): ?>
            <a href="main.php" class="<?= $current_page === 'main.php' ? 'active' : '' ?>">Main Page</a>
            <a href="patients.php" class="<?= $current_page === 'patients.php' ? 'active' : '' ?>">Patients</a>
            <a href="doctor_appointments.php" class="<?= $current_page === 'doctor_appointments.php' ? 'active' : '' ?>">Appointments</a>
            <a href="#about">About Us</a>
            <a href="logout.php">Logout</a>
        <?php elseif ($user_type === 'user'): ?>
            <a href="main.php" class="<?= $current_page === 'main.php' ? 'active' : '' ?>">Main Page</a>
            <a href="doctors.php" class="<?= $current_page === 'doctors.php' ? 'active' : '' ?>">Doctors</a>

            <a href="my_appointment.php" class="<?= $current_page === 'my_appointments.php' ? 'active' : '' ?>">My Appointments</a>
            <a href="#about">About Us</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= $user_type === 'doctor' ? 'doctor_profile.php' : 'profile.php' ?>">
            <img src="images/profile.jpg" class="profile-icon" alt="Profile">
        </a>
    <?php endif; ?>
</div>