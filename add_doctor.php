<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $bio = $_POST['bio'] ?? ''; // Optional
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "images/doctors/";
        $image = $targetDir . uniqid() . "_" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $stmt = $conn->prepare("INSERT INTO doctors (name, specialty, email, phone, image, bio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $specialty, $email, $phone, $image, $bio);
    if ($stmt->execute()) {
        header("Location: doctors.php");
        exit();
    } else {
        echo "Error adding doctor: " . $conn->error;
    }
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Doctor</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #9f71a5, #ebd2f5);
            margin: 0; padding: 0;
        }
        .container {
            width: 80%; max-width: 800px; margin: 50px auto;
            background-color: white; padding: 30px;
            border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 { text-align: center; color: #333; }
        label { display: block; margin-top: 15px; color: #555; }
        input, textarea {
            width: 100%; padding: 10px; margin-top: 5px;
            border-radius: 5px; border: 1px solid #ccc;
        }
        button {
            display: block; width: 100%; padding: 15px; margin-top: 20px;
            background-color: #9f71a5; border: none;
            color: white; font-size: 18px; border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: #7e4f7f; }
    </style>
</head>
<body>
<div class="container">
    <h1>Add a New Doctor</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Doctor Name</label>
        <input type="text" name="name" required>

        <label for="specialty">Specialty</label>
        <input type="text" name="specialty" required>

        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="phone">Phone</label>
        <input type="text" name="phone" required>

        <label for="bio">Short Bio (optional)</label>
        <textarea name="bio"></textarea>

        <label for="image">Profile Image</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Doctor</button>
    </form>
</div>
</body>
</html>
