<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Database connection

$user_id = $_SESSION['user_id'];

// Fetch user appointments with doctor details
$sql = "SELECT 
            a.appointment_id, 
            a.status,
            d.name AS doctor_name, 
            a.note AS doctor_note, 
            a.diagnosis, 
            s.slot_date, 
            s.slot_time
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        JOIN slots s ON a.slot_id = s.slot_id
        WHERE a.patient_id = :user_id
        ORDER BY s.slot_date, s.slot_time";

$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status messages
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = 'Appointment cancelled successfully.';
    } elseif ($_GET['status'] === 'too_late') {
        $message = 'Cannot cancel appointment less than 2 hours before.';
    } else {
        $message = 'Failed to cancel appointment.';
    }
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments - Derma Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #ebe3f5, #d8d3ea);
        }

        .cancel-button {
            background-color: #e74c3c;
            color: white;
            padding: 7px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }
        .cancel-button:hover {
            background-color: #c0392b;
        }
        .disabled-button {
            color: gray;
            font-style: italic;
        }
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #8e44ad;
            color: white;
        }
        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            color: #4a235a;
            font-size: 14px;
            margin-top: auto;
        }
        .status-accepted {
            color: #28a745;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }

        /* Popup styles */
        .popup-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
            pointer-events: none;
        }
        .popup-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .popup {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .popup h3 {
            margin: 0 0 15px;
            font-size: 20px;
            color: #4a235a;
        }

        .popup button {
            padding: 8px 20px;
            background-color: #8e44ad;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .popup button:hover {
            background-color: #732d91;
        }
    </style>
</head>
<body>

<h2 style="text-align:center; margin-top: 40px;">My Appointments</h2>

<?php if (!empty($message)): ?>
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup">
            <h3><?= htmlspecialchars($message) ?></h3>
            <button onclick="document.getElementById('popupOverlay').style.display='none'">OK</button>
        </div>
    </div>
<?php endif; ?>

<?php if (count($appointments) > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
            <th>Diagnosis</th>
            <th>Doctor's Note</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $now = new DateTime();
        foreach ($appointments as $row):
            $slotDateTime = new DateTime($row['slot_date'] . ' ' . $row['slot_time']);
            $hoursUntil = ($slotDateTime > $now) ? ($slotDateTime->getTimestamp() - $now->getTimestamp()) / 3600 : 0;
            ?>
            <tr>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['slot_date']) ?></td>
                <td><?= htmlspecialchars($row['slot_time']) ?></td>
                <td><?= htmlspecialchars($row['diagnosis']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['doctor_note'])) ?></td>
                <td>
                    <?php
                    if ($row['status'] === 'Accepted') {
                        echo '<span class="status-accepted">✅ Accepted</span>';
                    } elseif ($row['status'] === 'Rejected') {
                        echo '<span class="status-rejected">❌ Rejected</span>';
                    } else {
                        echo '<span class="status-pending">⏳ Pending</span>';
                    }
                    ?>
                </td>
                <td>
                    <?php if ($hoursUntil >= 2): ?>
                        <a href="cancel_appointment.php?id=<?= $row['appointment_id'] ?>" class="cancel-button" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                    <?php else: ?>
                        <span class="disabled-button">Cannot Cancel</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center; margin-top: 50px;">No appointments found.</p>
<?php endif; ?>

<!-- Footer -->
<footer>
    &copy; 2025 Derma Clinic. All rights reserved.
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

<script>
    window.onload = function() {
        const popup = document.getElementById('popupOverlay');
        if (popup) {
            popup.classList.add('show');
        }
    };
</script>

</body>
</html>
