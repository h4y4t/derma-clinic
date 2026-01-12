<?php
$servername = "localhost";
$username = "root"; // or your MySQL username
$password = ""; // your MySQL password
$database = "derma_clinic";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
