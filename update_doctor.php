<?php
include 'db_connection.php';

$id = $_POST['id'];
$name = $_POST['name'];
$specialty = $_POST['specialty'];
$phone = $_POST['phone'];
$email = $_POST['email'];

// Handle image update if uploaded
$image_sql = "";
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $image_path = 'uploads/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    $image_sql = ", image = '$image_path'";
}

$sql = "UPDATE doctors SET 
            name = '$name', 
            specialty = '$specialty', 
            phone = '$phone', 
            email = '$email'
            $image_sql
        WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: doctors_list.php");
} else {
    echo "Error updating doctor: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
