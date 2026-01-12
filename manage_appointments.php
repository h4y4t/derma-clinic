<?php
include 'db_connection.php';

// Fetch appointments with doctor, patient, and slot info
$sql = "SELECT a.appointment_id, a.status, a.note, a.diagnosis,
               d.name AS doctor_name,
               p.fullname AS patient_name,
               s.slot_date, s.slot_time
        FROM appointments a
        LEFT JOIN doctors d ON a.doctor_id = d.id
        LEFT JOIN patient_profile p ON a.patient_id = p.id
        LEFT JOIN slots s ON a.slot_id = s.slot_id
        ORDER BY a.appointment_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #9f71a5, #ebd2f5);
            padding: 30px;
        }
        h2 {
            text-align: center;
            color: #6b2d84;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: linear-gradient(to right, #a26ea1, #dec0f1);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-button {
            padding: 6px 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .action-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>Booked Appointments</h2>
<?php if (isset($_GET['status']) && $_GET['status'] === 'cancelled'): ?>
    <p style="color: green; text-align: center;">Appointment cancelled successfully.</p>
<?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
    <p style="color: red; text-align: center;">Error cancelling appointment.</p>
<?php endif; ?>
<table>
    <thead>
    <tr>
        <th>Appointment ID</th>
        <th>Doctor Name</th>
        <th>Patient Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['appointment_id'] ?></td>
                <td><?= $row['doctor_name'] ?? 'Unknown' ?></td>
                <td><?= $row['patient_name'] ?? 'Unknown' ?></td>
                <td><?= $row['slot_date'] ?? 'N/A' ?></td>
                <td><?= $row['slot_time'] ?? 'N/A' ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>
                    <form method="post" action="admin_cancel_appointment.php" style="margin: 0;">
                        <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                        <button type="submit" class="action-button">Cancel</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No appointments found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>