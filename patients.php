<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

$doctor_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT u.id, u.name, u.email
        FROM users u
        JOIN appointments a ON u.id = a.patient_id
        WHERE a.doctor_id = ? AND u.user_type = 'user'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Patients</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        .nav-links a {
            color: white;
            background-color: transparent;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 20px;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #8e44ad;
            color: white;
        }

        .nav-links a.active {
            background-color: #8e44ad;
            color: white;
        }
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #f4eafc, #e6d6f0);
        }

        h2 {
            text-align: center;
            color: #6a0dad;
            margin: 40px 0 30px;
        }

        .outer-wrapper {
            display: flex;
            justify-content: center;
            padding: 0 20px 60px;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            width: 100%;
            max-width: 1200px;
        }

        .patient-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .patient-card:hover {
            transform: translateY(-4px);
        }

        .patient-name {
            font-size: 20px;
            font-weight: bold;
            color: #4a235a;
            margin-bottom: 10px;
        }

        .patient-email {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .view-btn {
            background-color: #a16bdc;
            color: white;
            border: none;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #8b5fbf;
        }

        .no-patients {
            text-align: center;
            color: #a00;
            font-size: 16px;
            margin-top: 60px;
        }

        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            color: #4a235a;
            font-size: 14px;
            margin-top: auto;
        }

        footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<h2>My Patients</h2>

<?php if ($result->num_rows > 0): ?>
    <div class="outer-wrapper">
        <div class="card-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="patient-card">
                    <div class="patient-name"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="patient-email"><?= htmlspecialchars($row['email']) ?></div>
                    <a class="view-btn" href="patient_profile_view.php?user_id=<?= $row['id'] ?>">View Profile</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php else: ?>
    <p class="no-patients">No patients have booked an appointment yet.</p>
<?php endif; ?>

<footer>
    &copy; 2025 Derma Clinic. All rights reserved.
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

</body>
</html>

