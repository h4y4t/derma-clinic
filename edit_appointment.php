<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "No appointment ID specified.";
    exit();
}

$appointment_id = intval($_GET['id']);
$doctor_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = trim($_POST['diagnosis']);
    $note = trim($_POST['note']);

    $stmt = $conn->prepare("UPDATE appointments SET diagnosis = ?, note = ? WHERE appointment_id = ? AND doctor_id = ?");
    $stmt->bind_param("ssii", $diagnosis, $note, $appointment_id, $doctor_id);
    $stmt->execute();

    header("Location: doctor_appointments.php");
    exit();
}

// Fetch existing data
$stmt = $conn->prepare("SELECT diagnosis, note FROM appointments WHERE appointment_id = ? AND doctor_id = ?");
$stmt->bind_param("ii", $appointment_id, $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_page = ''; // no tab highlighted
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f4e9f8, #e6d6f0);
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
        }

        .form-container {
            background: #fff;
            max-width: 700px;
            width: 100%;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .form-container h2 {
            text-align: center;
            color: #6a0dad;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            color: #5e3393;
            display: block;
            margin-top: 20px;
            font-size: 16px;
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            resize: vertical;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 25px;
            margin-top: 30px;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #732d91;
        }

        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #4a235a;
        }
    </style>
</head>
<body>

<main>
    <div class="form-container">
        <h2>Add Diagnosis / Notes</h2>
        <form method="POST">
            <label for="diagnosis">Diagnosis:</label>
            <textarea name="diagnosis" id="diagnosis" required><?= htmlspecialchars($row['diagnosis'] ?? '') ?></textarea>

            <label for="note">Additional Notes / Prescription:</label>
            <textarea name="note" id="note"><?= htmlspecialchars($row['note'] ?? '') ?></textarea>

            <button type="submit">Save</button>
        </form>
    </div>
</main>

<footer>
    &copy; 2025 Derma Clinic. All rights reserved.
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

</body>
</html>
