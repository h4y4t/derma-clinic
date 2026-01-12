<?php
include 'db_connection.php'; // Ensure $conn is defined

// CONFIGURATION
$start_hour = 9;
$end_hour = 16; // 4 PM, exclusive
$slot_duration = 30; // minutes
$doctor_ids = [1, 2, 3, 4, 5]; // List of doctors (you can also query this from the database)

// Prepare statement to avoid inserting duplicate slots
$check_slot_stmt = $conn->prepare("SELECT slot_id FROM slots WHERE doctor_id = ? AND slot_date = ? AND slot_time = ?");
$insert_slot_stmt = $conn->prepare("INSERT INTO slots (doctor_id, slot_date, slot_time, is_available) VALUES (?, ?, ?, 1)");

$today = new DateTime();
$interval = new DateInterval('P1D');
$period = new DatePeriod($today, $interval, 14); // 14 days from today

foreach ($period as $day) {
    foreach ($doctor_ids as $doctor_id) {
        // Loop through the hours of the day (9:00 AM to 4:00 PM with half-hour slots)
        for ($hour = $start_hour; $hour < $end_hour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += $slot_duration) {
                // Format the slot time (e.g., 09:00:00)
                $slot_time = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':00';

                // Format the full date for the slot (e.g., 2025-06-08)
                $slot_date = $day->format('Y-m-d');

                // Check if slot already exists for this doctor, date, and time
                $check_slot_stmt->bind_param("iss", $doctor_id, $slot_date, $slot_time);
                $check_slot_stmt->execute();
                $check_slot_stmt->store_result();

                if ($check_slot_stmt->num_rows === 0) {
                    // Insert new slot if it doesn't already exist
                    $insert_slot_stmt->bind_param("iss", $doctor_id, $slot_date, $slot_time);
                    $insert_slot_stmt->execute();
                }
            }
        }
    }
}

$check_slot_stmt->close();
$insert_slot_stmt->close();
$conn->close();

echo "Slots generated for all doctors from 9:00 AM to 4:00 PM.";
?>