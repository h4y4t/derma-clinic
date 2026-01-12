<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
$current_page = 'appointments';

// Accept/Reject logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['action'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $status = ($_POST['action'] === 'accept') ? 'Accepted' : 'Rejected';
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ? AND doctor_id = ?");
    $stmt->bind_param("sii", $status, $appointment_id, $doctor_id);
    $stmt->execute();
}

// Fetch appointments
$query = "
    SELECT 
        a.appointment_id, a.status, a.slot_id, a.note, a.diagnosis,
        s.slot_date, s.slot_time,
        u.name AS patient_name, u.id AS patient_id,
        pp.skin_concern, pp.skin_type, pp.allergies, pp.medications,
        pp.treatment_history, pp.family_history, pp.contact_method
    FROM appointments a
    JOIN users u ON a.patient_id = u.id
    LEFT JOIN patient_profile pp ON pp.user_id = u.id
    LEFT JOIN slots s ON a.slot_id = s.slot_id
    WHERE a.doctor_id = ?
    ORDER BY a.appointment_id DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Appointments</title>
    <link rel="stylesheet" href="style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #ebe3f5, #d8d3ea);
            color: #333;
        }

        main {
            flex: 1;
        }

        h2.title {
            color: #5e3393;
            text-align: center;
            margin: 40px 0 20px;
        }

        .appointment-card {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .field-label {
            font-weight: bold;
            color: #6a0dad;
        }

        .field-content {
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #8e44ad;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #732d91;
        }

        .btn-pair {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .accept-btn {
            background-color: #28a745;
        }

        .reject-btn {
            background-color: #dc3545;
        }

        .status-accepted {
            color: #28a745;
            font-weight: bold;
        }
        .status-accepted::before {
            content: '✅ ';
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .status-pending::before {
            content: '⏳ ';
        }

        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .status-rejected::before {
            content: '❌ ';
        }

        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            color: #4a235a;
            font-size: 14px;
        }
        body {
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* optional: keeps background fixed when scrolling */
        }

    </style>
</head>
<body>

<main>
    <h2 class="title">Doctor Appointments</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="appointment-card">
                <div class="field-content"><span class="field-label">Patient Name:</span> <?= htmlspecialchars($row['patient_name']) ?></div>
                <div class="field-content"><span class="field-label">Appointment Date:</span> <?= htmlspecialchars($row['slot_date']) ?></div>
                <div class="field-content"><span class="field-label">Appointment Time:</span> <?= htmlspecialchars($row['slot_time']) ?></div>
                <div class="field-content"><span class="field-label">Status:</span>
                    <?php
                    if ($row['status'] === 'Accepted') {
                        echo '<span class="status-accepted">Accepted</span>';
                    } elseif ($row['status'] === 'Rejected') {
                        echo '<span class="status-rejected">Rejected</span>';
                    } else {
                        echo '<span class="status-pending">Pending</span>';
                    }
                    ?>
                </div>

                <?php if ($row['status'] === 'Pending'): ?>
                    <form method="POST" class="btn-pair">
                        <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                        <button type="submit" name="action" value="accept" class="btn accept-btn">Accept</button>
                        <button type="submit" name="action" value="reject" class="btn reject-btn">Reject</button>
                    </form>
                <?php elseif ($row['status'] === 'Accepted'): ?>
                    <div class="btn-pair">
                        <a href="edit_appointment.php?id=<?= $row['appointment_id'] ?>" class="btn">Add Info</a>
                        <a href="patient_profile_view.php?user_id=<?= $row['patient_id'] ?>" class="btn">View Medical Info</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; margin-top: 50px;">No appointments found.</p>
    <?php endif; ?>
</main>

<footer>
    &copy; 2025 Derma Clinic. All rights reserved.
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

</body>
</html>
