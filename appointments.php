<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

if (!isset($_GET['doctor_id'])) {
    echo "Doctor not specified.";
    exit();
}

$doctor_id = $_GET['doctor_id'] ?? null;
$doctor_name = 'Unknown Doctor';
$doctor_image = 'default-doctor.jpg';

$stmt = $conn->prepare("SELECT name, specialty, image FROM doctors WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Doctor not found.";
    exit();
}

$doctor = $result->fetch_assoc();
$doctor_name = $doctor['name'];
$doctor_image = $doctor['image'];

// Fetch doctor info
$doctor_query = "SELECT name, specialty, image FROM doctors WHERE id = $doctor_id";
$doctor_result = mysqli_query($conn, $doctor_query);
if (!$doctor_result || mysqli_num_rows($doctor_result) == 0) {
    echo "Doctor not found.";
    exit();
}
$doctor = mysqli_fetch_assoc($doctor_result);

// Fetch slots (available & unavailable) for next 14 days
$today = date('Y-m-d');
$two_weeks_later = date('Y-m-d', strtotime('+14 days'));

$slots_query = "
    SELECT slot_id, slot_date, slot_time, is_available 
    FROM slots 
    WHERE doctor_id = $doctor_id 
    AND slot_date BETWEEN '$today' AND '$two_weeks_later' 
    ORDER BY slot_date, slot_time
";
$slots_result = mysqli_query($conn, $slots_query);

// Organize slots by date and time
$slots_by_date = [];
while ($slot = mysqli_fetch_assoc($slots_result)) {
    $date = $slot['slot_date'];
    $time = date('H:i', strtotime($slot['slot_time']));
    $slots_by_date[$date][$time] = $slot;
}

// Define timetable days (Sunday to Thursday only)
$days = [];
for ($i = 0; $i < 14; $i++) {
    $date = date('Y-m-d', strtotime("+$i days"));
    $weekday = date('N', strtotime($date)); // 1=Mon, ..., 7=Sun
    if (in_array($weekday, [7, 1, 2, 3, 4])) { // Sunday to Thursday
        $days[] = $date;
    }
}


// Define time slots 09:00 to 16:00 every 30 min
$times = [];
$start = strtotime('09:00');
$end = strtotime('16:00');
for ($t = $start; $t < $end; $t += 1800) {
    $times[] = date('H:i', $t);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Book Appointment - Derma Clinic</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <style>

        .logo {
            font-family: 'Caveat', cursive;
            font-size: 32px;
            color: #8e44ad;
            margin-bottom: 5px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }
        .main-section {
            flex: 1;
            padding: 40px 20px;
            max-width: 1000px;
            margin: auto;
        }
        .doctor-card {
            background: #f3edf3;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease forwards;
        }
        .doctor-card img {
            width: 100%;
            max-width: 350px;
            height: 260px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .doctor-card img:hover {
            transform: scale(1.05);
        }
        .doctor-card h3 {
            margin: 15px 0 5px;
            font-size: 22px;
            color: #6a0dad;
        }
        .doctor-card p {
            font-size: 18px;
            color: #333;
        }

        table.timetable {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
        table.timetable th, table.timetable td {
            border: 1px solid #ddd;
            padding: 8px;
            min-width: 90px;
            vertical-align: middle;
        }
        table.timetable th {
            background-color: #6a0dad;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        table.timetable td.available {
            background-color: #f0e6ff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        table.timetable td.available:hover {
            background-color: #d9b8ff;
        }
        table.timetable td.unavailable {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }
        table.timetable td button.slot-btn {
            all: unset;
            width: 100%;
            height: 100%;
            display: block;
            cursor: pointer;
            font-weight: bold;
            color: #6a0dad;
        }
        table.timetable td button.slot-btn.selected {
            background-color: #8e44ad;
            color: white;
            border-radius: 5px;
        }
        form button#confirmBtn {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form button#confirmBtn:disabled {
            background-color: #bbb;
            cursor: not-allowed;
        }
        form button#confirmBtn:hover:enabled {
            background-color: #732d91;
        }

        /* Navbar and footer styles - reuse your previous styles here */

        .navbar {
            display: flex;
            align-items: center;
            padding: 15px 30px;
            background: linear-gradient(135deg, #e6ccff, #d8b4e2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            gap: 40px;
            flex-wrap: wrap;
        }

        .header-left .motto {
            font-size: 14px;
            color: #888;
        }
        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #000000;
            font-weight: bold;
        }
        .nav-links a.active {
            text-decoration: underline;
        }
        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            color: #4a235a;
            font-size: 14px;
            margin-top: auto;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .profile-icon {
            position: absolute;
            top: 25px;
            right: 30px;
            width: 40px;
            height: 40px;
            cursor: pointer;
            border-radius: 50%; /* هذه تجعل الصورة دائرية */
            object-fit: cover;   /* لضمان ظهور الصورة بشكل جميل داخل الدائرة */
        }
    </style>
</head>
<body>
<!-- Navbar -->
<div class="navbar">
    <div class="header-left">
        <div class="logo">Derma Clinic</div>
        <div class="motto">"Your Skin, Our Priority"</div>
    </div>
    <div class="nav-links">
        <a href="main.php" >Main Page</a>
        <a href="doctors.php"class="active">Doctors</a>
        <a href="#about">About Us</a>
        <a href="my_appointment.php">My Appointment</a>
    </div>
    <!-- Profile Icon (Top Right Corner) -->
    <a href="profile.php">
        <img src="images/profile.jpg" class="profile-icon" alt="Profile Icon">
    </a>
</div>

<!-- Main Content -->
<div class="main-section">
    <!-- Doctor Card -->
    <div class="doctor-card">

        <img src="images/doctors/<?= htmlspecialchars($doctor_image) ?>" alt="<?= htmlspecialchars($doctor_name) ?>">
        <h3> <?= htmlspecialchars($doctor['name']) ?>
        </h3>
        <p><?= htmlspecialchars($doctor['specialty']) ?></p>
    </div>

    <!-- Appointment timetable form -->
    <form action="submit_appointment.php" method="POST" id="appointmentForm">
        <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>" />
        <input type="hidden" name="slot_id" id="slot_id" required />

        <table class="timetable" aria-label="Available appointment slots">
            <thead>
            <tr>
                <th>Time</th>
                <?php foreach ($days as $day): ?>
                    <th><?= date('l, M j', strtotime($day)) ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($times as $time): ?>
                <tr>
                    <td><?= date('g:i A', strtotime($time)) ?></td>
                    <?php foreach ($days as $day):
                        $slot = $slots_by_date[$day][$time] ?? null;
                        if ($slot) {
                            $available = $slot['is_available'] == 1;
                            $slot_id = $slot['slot_id'];
                        } else {
                            $available = false;
                            $slot_id = null;
                        }
                        ?>
                        <td class="<?= $available ? 'available' : 'unavailable' ?>"
                            data-slot-id="<?= $slot_id ?>"
                            <?= $available ? '' : 'style="background-color:#ccc; color:#666;"' ?>
                        >
                            <?php if ($available): ?>
                                <button type="button" class="slot-btn" aria-pressed="false">
                                    Available
                                </button>
                            <?php else: ?>
                                Unavailable
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" id="confirmBtn" disabled>Confirm Appointment</button>
    </form>
</div>

<!-- Footer -->
<footer id="about">
    &copy; <?= date('Y') ?> Derma Clinic. All rights reserved.
</footer>

<script>
    // JS: Allow selecting one available slot at a time
    const slotButtons = document.querySelectorAll('.slot-btn');
    const slotIdInput = document.getElementById('slot_id');
    const confirmBtn = document.getElementById('confirmBtn');

    slotButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove 'selected' from all buttons
            slotButtons.forEach(b => {
                b.classList.remove('selected');
                b.setAttribute('aria-pressed', 'false');
            });
            // Mark clicked button as selected
            btn.classList.add('selected');
            btn.setAttribute('aria-pressed', 'true');

            // Update hidden input value
            const slotId = btn.parentElement.getAttribute('data-slot-id');
            slotIdInput.value = slotId;

            // Enable confirm button
            confirmBtn.disabled = false;
        });
    });
</script>

</body>
</html>