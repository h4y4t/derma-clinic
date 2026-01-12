<?php
require_once 'db.php'; // أو db_connection.php إذا هذا هو اسم ملف الاتصال عندك

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // جلب slot_id المرتبط بالموعد
    $stmt = $conn->prepare("SELECT slot_id FROM appointments WHERE appointment_id = ?");
    $stmt->execute([$appointment_id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        try {
            $conn->beginTransaction();

            // حذف الموعد من جدول appointments
            $deleteStmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id = ?");
            $deleteStmt->execute([$appointment_id]);

            // تحديث توفر الموعد في جدول slots
            $updateSlot = $conn->prepare("UPDATE slots SET is_available = 1 WHERE slot_id = ?");
            $updateSlot->execute([$appointment['slot_id']]);

            $conn->commit();

            // إعادة التوجيه مع رسالة نجاح
            header("Location: manage_appointments.php?status=cancelled");
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            // إعادة التوجيه مع رسالة خطأ
            header("Location: manage_appointments.php?status=error");
            exit();
        }
    } else {
        // الموعد غير موجود
        header("Location: manage_appointments.php?status=not_found");
        exit();
    }
} else {
    // طلب غير صالح
    header("Location: manage_appointments.php?status=invalid");
    exit();
}