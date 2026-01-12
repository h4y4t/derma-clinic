<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // First check users table
    $stmt = $conn->prepare("SELECT id, password, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows === 1) {
        $user = $user_result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_type'] = 'user';
            header("Location:main.php");
            exit();
        } else {
            header("Location: login.php?error=Incorrect password.");
            exit();
        }
    }

    // Then check doctors table
    $stmt = $conn->prepare("SELECT id, password, name FROM doctors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $doctor_result = $stmt->get_result();

    if ($doctor_result->num_rows === 1) {
        $doctor = $doctor_result->fetch_assoc();
        if (password_verify($password, $doctor['password'])) {
            $_SESSION['user_id'] = $doctor['id'];
            $_SESSION['name'] = $doctor['name'];
            $_SESSION['user_type'] = 'doctor';
            header("Location: main.php");
            exit();
        } else {
            header("Location: login.php?error=Incorrect password.");
            exit();
        }
    }

    // If not found
    header("Location: login.php?error=Account not found.");
    exit();
}
?>
