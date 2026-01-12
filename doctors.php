<?php

$conn = new mysqli("localhost", "root", "", "derma_clinic");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT id, name, specialty, email, phone, image FROM doctors");
if ($result->num_rows === 0) {
    echo "No doctors found.";
    exit;
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Doctors - Derma Clinic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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

        /* Fade-in Up Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .intro-line {
            font-size: 28px;
            font-weight: bold;
            color: #6a1b9a;
            margin-bottom: 40px;
            text-align: center;
        }
        .profile-icon {
            position: absolute;
            top: 25px;
            right: 30px;
            width: 40px;
            height: 40px;
            cursor: pointer;
            border-radius: 50%;
            object-fit: cover;
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


<!-- Introductory Sentence -->
<p class="intro-line fade-in" style="animation-delay: 0.2s;">Meet Our Dermatology Experts - Offering Expert Care for Your Skin</p>

<!-- Doctors Section -->
<h2 style="text-align:center; margin-top:40px;" class="fade-in" style="animation-delay: 0.3s;">Our Dermatologists</h2>

<div class="doctor-grid">
    <?php
    $counter = 0;
    while ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $specialty = $row['specialty'];
        $image = 'images/doctors/' . $row['image'];
        $doctor_id = $row['id'];
        $delay = 0.4 + ($counter * 0.2); // start from 0.4s

        echo "
        <div class='doctor-card fade-in' style='animation-delay: {$delay}s;'>
            <img src='$image' alt='$name'>
            <h3>$name</h3>
            <p><strong>Specialty:</strong> $specialty</p>
            <p><strong>Email:</strong> {$row['email']}</p>
            <p><strong>Phone:</strong> {$row['phone']}</p>
            <a href='appointments.php?doctor_id=$doctor_id' class='book-button'>Book Appointment</a>
        </div>
        ";
        $counter++;
    }
    ?>
</div>

<!-- Footer -->
<footer id="about">
    <p>© 2025 Derma Clinic. All rights reserved.</p>
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

</body>
</html>

<?php $conn->close(); ?>
