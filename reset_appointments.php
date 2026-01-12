<?php
include 'db.php'; // Connect to database

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_days = $_POST['days'] ?? [];
    $times = ['09:00 AM','09:30 AM', '10:00 AM','10:30 AM', '11:00 AM','11:30 AM',
        '12:00 PM','12:30 PM', '01:00 PM','01:30 PM', '02:00 PM', '02:30 PM',
        '03:00 PM', '03:30 PM'];
    $today = date('Y-m-d');
// Step 1: Delete appointments related to the slots (from the appointments table) for the upcoming dates
    $conn->exec("DELETE FROM appointments WHERE slot_id IN (SELECT slot_id FROM slots WHERE slot_date >= '$today')");

    // Step 2: Delete old slots in the slots table (starting from today)
    $conn->exec("DELETE FROM slots WHERE slot_date >= '$today'");

    //Step 3: Get all doctor IDs
    $stmt = $conn->query("SELECT id FROM doctors");
    $doctor_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //Step 4: Generate new appointments for next 14 days
    for ($i = 1; $i <= 14; $i++) {
        $date = date('Y-m-d', strtotime("+$i days"));
        $day_name = date('l', strtotime($date));

        if (in_array($day_name, $selected_days)) {
            foreach ($doctor_ids as $doctor_id) {
                foreach ($times as $time) {
                    $datetime = date('Y-m-d H:i:s', strtotime("$date $time"));

                    $check_stmt = $conn->prepare("SELECT slot_id FROM slots WHERE doctor_id = ? AND slot_date = ? AND slot_time = ?");
                    $check_stmt->execute([$doctor_id, $date, $datetime]);

                    if ($check_stmt->rowCount() == 0) {
                        $stmt = $conn->prepare("INSERT INTO slots (doctor_id, slot_date, slot_time, is_available) VALUES (?, ?, ?, 1)");
                        $stmt->execute([$doctor_id, $date, $datetime]);
                    }
                }
            }
        }
    }

    $success = "The timetable has been updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard - Reset Appointments</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #d6c1e6, #d8d3ea);
        }

        .navbar {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #e6ccff, #d8b4e2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-family: 'Caveat', cursive;
            font-size: 32px;
            color: #8e44ad;
            margin-bottom: 5px;
        }

        .motto {
            font-size: 14px;
            color: #6a0dad;
        }

        .main-section {
            flex: 1;
            max-width: 700px;
            margin: 60px auto 40px;
            padding: 50px 40px;
            background: rgba(255, 255, 255, 0.25);
            border: 2px solid rgba(216,180,226,0.9);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            color: #4a235a;
            text-align: center;
            animation: fadeIn 0.6s ease-in-out forwards;
        }

        .main-section h2 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }

        .main-section p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .success-message {
            font-size: 20px;
            margin: 20px 0;
            font-weight: bold;
            color: #2c7a2c;
        }

        form {
            text-align: left;
            max-width: 400px;
            margin: 0 auto;
        }

        .day-option {
            margin-bottom: 12px;
            font-size: 17px;
        }

        .day-option label {
            display: flex;
            align-items: center;
        }

        input[type="checkbox"] {
            transform: scale(1.3);
            margin-right: 12px;
            cursor: pointer;
        }

        input[type="submit"] {
            margin-top: 25px;
            padding: 14px 36px;
            background: linear-gradient(135deg, #8e44ad, #6a0dad);
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 30px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 6px 16px rgba(110, 43, 171, 0.55);
            transition: background 0.3s ease, transform 0.2s ease;
            display: block;
            margin: 30px auto 0;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #6a0dad, #8e44ad);
            transform: scale(1.05);
        }

        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            color: #4a235a;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">Derma Clinic</div>
    <div class="motto">Your Skin, Our Priority</div>
</div>

<!-- Main Section -->
<div class="main-section">
    <h2>Reset Appointments</h2>

    <?php if (!empty($success)): ?>
        <div class="success-message"><?= $success ?></div>
    <?php else: ?>
        <p>Select the weekdays to reset doctors' appointments for the upcoming 2 weeks.</p>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="day-option"><label><input type="checkbox" name="days[]" value="Sunday" checked> Sunday</label></div>
        <div class="day-option"><label><input type="checkbox" name="days[]" value="Monday" checked> Monday</label></div>
        <div class="day-option"><label><input type="checkbox" name="days[]" value="Tuesday" checked> Tuesday</label></div>
        <div class="day-option"><label><input type="checkbox" name="days[]" value="Wednesday" checked> Wednesday</label></div>
        <div class="day-option"><label><input type="checkbox" name="days[]" value="Thursday" checked> Thursday</label></div>

        <input type="submit" value="Reset Appointments">
    </form>
</div>

<!-- Footer -->
<footer>
    &copy; <?= date('Y') ?> Derma Clinic. All rights reserved.<br>
    Contact us: info@dermaclinic.com | +1 (123) 456-7890
</footer>

</body>
</html>
