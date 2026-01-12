<?php
session_start();
include 'db_connection.php';

// Only doctors can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

// Require patient user_id
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo "No patient specified.";
    exit();
}

$patient_id = intval($_GET['user_id']);

// Fetch patient profile
$stmt = $conn->prepare("SELECT * FROM patient_profile WHERE user_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();


if (!$data) {
    echo "Patient profile not found.";
    exit();
}

$current_page = '';
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Medical Info</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #f4eafc, #e6d6f0);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #333;
        }

        main {
            flex: 1;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #6a0dad;
            margin-bottom: 30px;
        }

        .accordion-header {
            background-color: #f5e7fd;
            color: #4a235a;
            font-weight: bold;
            cursor: pointer;
            padding: 14px 20px;
            border: 1px solid #caa7e8;
            border-radius: 8px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .accordion-header:hover {
            background-color: #ecd9f5;
        }

        .accordion-content {
            display: none;
            padding: 18px 20px;
            border: 1px solid #ddd;
            border-top: none;
            background: #fafafa;
            border-radius: 0 0 8px 8px;
        }

        .accordion-header.active + .accordion-content {
            display: block;
        }

        .arrow {
            font-size: 18px;
            transition: transform 0.3s;
        }

        .accordion-header.active .arrow {
            transform: rotate(90deg);
        }

        .profile-field {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #6a0dad;
            margin-bottom: 5px;
        }

        .readonly {
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 6px;
        }

        footer {
            background-color: #d8b4e2;
            text-align: center;
            padding: 20px;
            color: #4a235a;
            font-size: 14px;
            margin-top: auto;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const headers = document.querySelectorAll('.accordion-header');
            headers.forEach(header => {
                header.addEventListener('click', function () {
                    this.classList.toggle('active');
                });
            });
        });
    </script>
</head>
<body>

<main>
    <div class="container">
        <h2>Patient Profile</h2>

        <div class="accordion-header">Personal Info <span class="arrow">▶</span></div>
        <div class="accordion-content">
            <?php foreach ($data as $key => $value): ?>
                <?php if (in_array($key, ['fullname', 'birthdate', 'gender', 'phone', 'address', 'email'])): ?>
                    <div class="profile-field">
                        <label><?= ucfirst(str_replace("_", " ", $key)) ?>:</label>
                        <div class="readonly"><?= htmlspecialchars($value) ?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="accordion-header">Medical Info <span class="arrow">▶</span></div>
        <div class="accordion-content">
            <?php foreach ($data as $key => $value): ?>
                <?php if (!in_array($key, ['fullname', 'birthdate', 'gender', 'phone', 'address', 'email', 'id', 'user_id'])): ?>
                    <div class="profile-field">
                        <label><?= ucfirst(str_replace("_", " ", $key)) ?>:</label>
                        <div class="readonly"><?= htmlspecialchars($value) ?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<footer>
    &copy; 2025 Derma Clinic. All rights reserved.
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>

</body>
</html>
