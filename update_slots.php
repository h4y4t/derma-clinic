<?php
include 'db_connect.php'; // Ensure connection to the database

// CONFIGURATION
$start_hour = 9;
$end_hour = 16; // 4 PM, exclusive
$slot_duration = 30; // minutes
$doctor_ids = [1, 2, 3, 4, 5]; // List of doctors (can query this from the database if needed)

// Prepare statement to avoid inserting duplicate slots
$check_slot_stmt = $conn->prepare("SELECT slot_id FROM slots WHERE doctor_id = ? AND slot_time = ?");
$insert_slot_stmt = $conn->prepare("INSERT INTO slots (doctor_id, slot_time, is_available) VALUES (?, ?, 1)");

$today = new DateTime();
$interval = new DateInterval('P1D');
$period = new DatePeriod($today, $interval, 14); // 14 days from today

foreach ($period as $day) {
    foreach ($doctor_ids as $doctor_id) {
        // Loop through the hours of the day (9:00 AM to 4:00 PM with half-hour slots)
        for ($hour = $start_hour; $hour < $end_hour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += $slot_duration) {
                // Format the slot time
                $slot_time = $day->format('Y-m-d') . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':00';

                // Check if slot already exists
                $check_slot_stmt->bind_param("is", $doctor_id, $slot_time);
                $check_slot_stmt->execute();
                $check_slot_stmt->store_result();

                if ($check_slot_stmt->num_rows === 0) {
                    // Insert new slot if it doesn't already exist
                    $insert_slot_stmt->bind_param("is", $doctor_id, $slot_time);
                    $insert_slot_stmt->execute();
                }
            }
        }
    }
}

$check_slot_stmt->close();
$insert_slot_stmt->close();
$conn->close();

echo "Slots updated for all doctors from 9:00 AM to 4:00 PM.";
?>
