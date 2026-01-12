<?php
session_start();
require_once 'db.php';

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // جلب معلومات الموعد للتحقق من الزمن
    $stmt = $conn->prepare("
        SELECT a.slot_id, s.slot_date, s.slot_time 
        FROM appointments a 
        JOIN slots s ON a.slot_id = s.slot_id 
        WHERE a.appointment_id = ?
    ");
    $stmt->execute([$appointment_id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        $appointment_time = new DateTime($appointment['slot_date'] . ' ' . $appointment['slot_time']);
        $now = new DateTime();
        $diff_hours = ($appointment_time->getTimestamp() - $now->getTimestamp()) / 3600;

        if ($diff_hours > 2) {
            // إلغاء الموعد وتحديث الجدول
            $conn->beginTransaction();
            $conn->prepare("DELETE FROM appointments WHERE appointment_id = ?")->execute([$appointment_id]);
            $conn->prepare("UPDATE slots SET is_available = 1 WHERE slot_id = ?")->execute([$appointment['slot_id']]);
            $conn->commit();

            header("Location: my_appointment.php?status=success");
            exit();
        } else {
            header("Location: my_appointment.php?status=too_late");
            exit();
        }
    }
}

header("Location: my_appointment.php?status=error");
exit();