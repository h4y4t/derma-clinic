<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php'; // تأكد أن الاتصال يعمل

$patient_id = $_SESSION['user_id'];
$doctor_id = isset($_POST['doctor_id']) ? intval($_POST['doctor_id']) : 0;
$slot_id = isset($_POST['slot_id']) ? intval($_POST['slot_id']) : 0;

if ($doctor_id && $slot_id) {
    // تحقق من توفر الموعد
    $check_query = "SELECT is_available FROM slots WHERE slot_id = ? AND doctor_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $slot_id, $doctor_id);
    $stmt->execute();
    $stmt->bind_result($is_available);
    $stmt->fetch();
    $stmt->close();

    if ($is_available) {
        // جعل الموعد غير متاح
        $update_slot = "UPDATE slots SET is_available = 0 WHERE slot_id = ?";
        $stmt = $conn->prepare($update_slot);
        $stmt->bind_param("i", $slot_id);
        $stmt->execute();
        $stmt->close();

        // إدخال الحجز في جدول المواعيد
        $insert = "INSERT INTO appointments (doctor_id, patient_id, slot_id, status) VALUES (?, ?, ?, 'pending')";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("iii", $doctor_id, $patient_id, $slot_id);

        if ($stmt->execute()) {
            // تحويل مباشر لصفحة النجاح بدون JS
            header("Location: appointment_success.php");
            exit();
        } else {
            // فشل الإدخال - عرض رسالة خطأ بسيطة
            echo "فشل في حجز الموعد. حاول لاحقاً.";
            exit();

        }

        $stmt->close();
    } else {
        echo "هذا الموعد لم يعد متاحاً.";
        exit();

    }
} else {
    echo "طلب غير صحيح.";
    exit();
}
?>
<?php include 'header.php'; ?>
