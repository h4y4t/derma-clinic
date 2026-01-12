<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        header("Location: signup.php?error=" . urlencode("Passwords do not match."));
        exit();
    }

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        header("Location: signup.php?error=" . urlencode("User already exists with this email."));
        exit();
    }
    $check->close();

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hash);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        header("Location: login_success.php");
        exit();
    } else {
        header("Location: signup.php?error=" . urlencode("Something went wrong. Please try again."));
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: signup.php?error=" . urlencode("Invalid request."));
}
?>
