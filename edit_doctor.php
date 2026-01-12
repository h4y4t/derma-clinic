<?php
include 'db_connection.php';

if (!isset($_GET['id'])) {
    echo "Doctor ID is missing.";
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM doctors WHERE id = $id");

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Doctor not found.";
    exit;
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Handle optional image update
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $targetPath = 'images/doctors/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    } else {
        $imageName = $row['image'];
    }

    $update = "UPDATE doctors SET 
        name = '$name', 
        specialty = '$specialty', 
        phone = '$phone', 
        email = '$email', 
        image = '$imageName' 
        WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Doctor updated successfully.'); window.location.href='doctors_list.php';</script>";
        exit;
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #9f71a5, #ebd2f5);
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 60px auto;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        h2 {
            text-align: center;
            color: #6a1b9a;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #444;
        }
        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            margin-top: 30px;
            width: 100%;
            padding: 14px;
            font-size: 18px;
            background-color: #9f71a5;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #7e4f7f;
        }
        .current-image {
            margin-top: 10px;
        }
        .current-image img {
            width: 100px;
            border-radius: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Doctor</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Doctor Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>

        <label>Specialty</label>
        <input type="text" name="specialty" value="<?= htmlspecialchars($row['specialty']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

        <label>Profile Image (optional)</label>
        <input type="file" name="image" accept="image/*">
        <div class="current-image">
            <p>Current Image:</p>
            <img src="images/doctors/<?= htmlspecialchars($row['image']) ?>" alt="Doctor Image">
        </div>

        <button type="submit">Update Doctor</button>
    </form>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>
