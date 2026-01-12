<?php
// Connect to the database
include 'db_connection.php';

// Check if doctor ID is set
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid doctor ID.";
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM doctors WHERE id = $id");

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Doctor not found.";
    exit;
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Doctor Details</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #9f71a5, #ebd2f5);
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 700px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #6a0dad;
        }
        p {
            font-size: 18px;
            color: #444;
            margin: 10px 0;
        }
        img {
            margin-top: 20px;
            width: 250px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        a {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            background-color: #9f71a5;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        a:hover {
            background-color: #7e4f7f;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Doctor Details</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
    <p><strong>Specialty:</strong> <?= htmlspecialchars($row['specialty']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
    <?php if (!empty($row['image'])): ?>
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Doctor Image">
    <?php else: ?>
        <p><em>No image available.</em></p>
    <?php endif; ?>
    <br>
    <a href="doctors_list.php">← Back to List</a>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>
