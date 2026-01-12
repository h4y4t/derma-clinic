<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM doctors WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: doctors_list.php");
    } else {
        echo "Error deleting doctor: " . mysqli_error($conn);
    }
}
?>
