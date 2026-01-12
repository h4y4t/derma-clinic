<?php
$host = 'localhost';
$dbname = 'derma_clinic'; // اسم قاعدة البيانات
$username = 'root';       // غيّر إذا كان عندك اسم مستخدم مختلف
$password = '';           // غيّر إذا كان عندك كلمة مرور

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>