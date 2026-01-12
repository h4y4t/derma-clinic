<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Derma Clinic - Main Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <style>

        .doctor-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 50px auto;
            max-width: 1200px;
        }

        .doctor-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            padding: 20px;
            width: 280px;
            text-align: center;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease;
        }

        .doctor-card.show {
            opacity: 1;
            transform: translateY(0);
        }

        .doctor-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .doctor-card img:hover {
            transform: scale(1.05);
        }

        .doctor-card h3 {
            margin: 15px 0 5px;
            font-size: 22px;
            color: #6a0dad;
        }

        .doctor-card p {
            font-size: 18px;
            color: #333;
        }

        .book-button {
            margin-top: 10px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #8e44ad;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .book-button:hover {
            background-color: #732d91;
        }

        /* Additional styling for the page */
        .intro-line {
            font-size: 28px; /* Increase the font size */
            font-weight: bold;
            color: #6a1b9a;
            margin-bottom: 40px;
            animation: fadeIn 1s ease forwards;
            opacity: 0;
            transform: translateY(20px);
            text-align: center;
        }

        /* Fade-in Up Animation */
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation delay for cards */
        .doctor-card {
            animation-delay: 0.3s; /* Add delay for animation */
        }
        /* Profile Icon Styles */
        .profile-icon {
            position: absolute;
            top: 15px;
            right: 30px;
            width: 40px;
            height: 40px;
            cursor: pointer;
            border-radius: 50%; /* هذه تجعل الصورة دائرية */
            object-fit: cover;   /* لضمان ظهور الصورة بشكل جميل داخل الدائرة */
        }
        body {
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* optional: keeps background fixed when scrolling */
        }

    </style>
</head>
<body>

<!-- Main Content -->
<section class="main-section">
    <p class="intro-line fade-in" style="animation-delay: 0.2s;">Experience expert care and cutting-edge skin diagnostics, tailored for you.</p>

    <!-- Image Grid -->
    <div class="image-grid">
        <img src="images/pi3.jpg" alt="Skin Care 1" class="fade-in" style="animation-delay: 0.5s;">
        <img src="images/healthy.jpg" alt="Skin Care 2" class="fade-in" style="animation-delay: 0.7s;">
        <img src="images/pi2.jpg" alt="Skin Care 3" class="fade-in" style="animation-delay: 0.9s;">
        <img src="images/pi1.jpg" alt="Skin Care 4" class="fade-in" style="animation-delay: 1.1s;">
    </div>

    <!-- Action Cards -->
    <div class="action-buttons">
        <div class="action-item doctor-card fade-in" style="animation-delay: 1.4s;">
            <img src="images/booking.png" alt="Book Appointment">
            <p>Book a personalized consultation with a dermatologist near you.</p>
            <button onclick="location.href='doctors.php'">Book Appointment</button>
        </div>
        <div class="action-item doctor-card fade-in" style="animation-delay: 1.6s;">
            <img src="images/ai-tool.png" alt="AI Tool">
            <p>Use our AI-powered tool to analyze your skin condition instantly.</p>
            <button onclick="location.href='ai_tool.php'">Use AI Tool</button>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id ="about">
    &copy; 2025 Derma Clinic. All rights reserved.
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>

</footer>
</body>
</html>
