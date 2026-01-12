<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$patient_id = intval($_GET['patient_id']);
$result = $conn->query("
    SELECT u.email, p.skin_type, p.skin_concern, p.medications, p.treatment_history
    FROM users u
    LEFT JOIN patient_profile p ON u.email = p.email
    WHERE u.id = $patient_id
");
$data = $result->fetch_assoc();
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient Medical Info</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .info-box {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        .info-box h2 { color: #6a0dad; text-align: center; }
        .info-box p { font-size: 16px; line-height: 1.6; }
    </style>
</head>
<body>

<div class="info-box">
    <h2>Patient Info</h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
    <p><strong>Skin Type:</strong> <?= htmlspecialchars($data['skin_type']) ?></p>
    <p><strong>Skin Concern:</strong> <?= htmlspecialchars($data['skin_concern']) ?></p>
    <p><strong>Medications:</strong> <?= htmlspecialchars($data['medications']) ?></p>
    <p><strong>Treatment History:</strong> <?= htmlspecialchars($data['treatment_history']) ?></p>
</div>
<footer>
    <p>© 2025 Derma Clinic. All rights reserved.</p>
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>
</body>
</html>
