<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';
$doctor_id = $_SESSION['user_id'];

// Get doctor info
$stmt = $conn->prepare("SELECT name, email, specialty, image FROM doctors WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .doctor-container {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }

        .doctor-container h2 {
            color: #6a0dad;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .doctor-container p {
            font-size: 18px;
            color: #444;
            margin: 8px 0;
        }

        .doctor-image {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #a16bdc;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-appointments {
            background-color: #8e44ad;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 30px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-appointments:hover {
            background-color: #732d91;
        }
    </style>
</head>
<body>

<div class="doctor-container">
    <!-- Doctor Image -->
    <?php
    $imagePath = 'images/doctors/' . ($doctor['image'] ?? '');
    $displayImage = file_exists($imagePath) && !empty($doctor['image']) ? $imagePath : 'images/default_doctor.jpg';
    ?>
    <img src="<?= htmlspecialchars($displayImage) ?>" class="doctor-image" alt="Doctor Image">

    <!-- Doctor Info -->
    <h2>Welcome,  <?= htmlspecialchars($doctor['name']) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
    <p><strong>Specialty:</strong> <?= htmlspecialchars($doctor['specialty']) ?></p>

    <!-- Appointment Button -->
    <a href="doctor_appointments.php" class="btn-appointments">View Appointments</a>
</div>
<footer>
    <p>© 2025 Derma Clinic. All rights reserved.</p>
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>
</body>
</html>
