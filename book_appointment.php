<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the slot is still available
    $check = mysqli_query($conn, "SELECT * FROM appointments WHERE id = $appointment_id AND is_booked = 0");

    if (mysqli_num_rows($check) > 0) {
        $update = mysqli_query($conn, "UPDATE appointments SET is_booked = 1, patient_id = $user_id WHERE id = $appointment_id");

        if ($update) {
            header("Location: appointments.php?success=1");
            exit();
        } else {
            echo "Failed to book. Try again.";
        }
    } else {
        echo "Sorry, that slot was already taken.";
    }
} else {
    echo "Invalid request.";
}
?>
