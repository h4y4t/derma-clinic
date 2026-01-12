<?php
// fetch_appointments.php

include 'db_connection.php'; // Modify according to your database connection

if (isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];

    // Fetch available time slots for the selected doctor (modify query as per your structure)
    $query = "SELECT * FROM appointments WHERE doctor_id = $doctor_id AND appointment_date >= CURDATE() AND status = 'available' ORDER BY appointment_date ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<button onclick='bookAppointment(" . $row['appointment_id'] . ")'>" . $row['appointment_date'] . " - " . $row['appointment_time'] . "</button>";
        }
    } else {
        echo "<p>No available slots for this doctor.</p>";
    }
}
?>
