<?php
session_start();
$conn = new mysqli("localhost", "root", "", "derma_clinic");

if (!isset($_SESSION['user_id'])) {
    die("Please log in to book.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Prevent duplicates
    $stmt = $conn->prepare("SELECT id FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ?");
    $stmt->bind_param("iss", $doctor_id, $date, $time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "This slot is already booked. <a href='appointments.php'>Go back</a>";
    } else {
        // Book appointment
        $insert = $conn->prepare("INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiss", $doctor_id, $user_id, $date, $time);
        if ($insert->execute()) {
            // Redirect to appointments page after successful booking
            header("Location: appointments.php?status=booked");
            exit();
        } else {
            echo "Error booking appointment: " . $insert->error;
        }
    }

    $stmt->close();
    $insert->close();
}

$conn->close();
?>
