<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'derma_clinic');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();

}
include 'header.php';

$user_id = $_SESSION['user_id'];
$userResult = $conn->query("SELECT id, email FROM users WHERE id='$user_id'");
$user = $userResult->fetch_assoc();
$email = $user['email'];

$result = $conn->query("SELECT * FROM patient_profile WHERE email='$email'");
$data = $result->fetch_assoc();
$profileSaved = false;
$updateSuccess = false;

$_SESSION['email'] = $user['email'];


// Insert profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$data && !isset($_POST['update'])) {
    $stmt = $conn->prepare("INSERT INTO patient_profile (email, fullname, birthdate, gender, phone, address, skin_type, skin_concern, allergies, medications, treatment_history, family_history, contact_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $email, $_POST['fullname'], $_POST['birthdate'], $_POST['gender'], $_POST['phone'], $_POST['address'], $_POST['skin_type'], $_POST['skin_concern'], $_POST['allergies'], $_POST['medications'], $_POST['treatment_history'], $_POST['family_history'], $_POST['contact_method']);
    $stmt->execute();

    $profileSaved = true;
    $data = $_POST;
}

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $fields = ['skin_type', 'skin_concern', 'allergies', 'medications', 'treatment_history', 'family_history', 'contact_method'];
    $updates = [];
    foreach ($fields as $field) {
        $updates[] = "$field='" . $conn->real_escape_string($_POST[$field]) . "'";
    }
    $updateSql = "UPDATE patient_profile SET " . implode(", ", $updates) . " WHERE email='$email'";
    $conn->query($updateSql);
    $result = $conn->query("SELECT * FROM patient_profile WHERE email='$email'");
    $data = $result->fetch_assoc();
    $updateSuccess = true;
}
// Get appointments with doctor names
$appointments = $conn->query("
    SELECT a.*, d.name AS doctor_name
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    WHERE a.patient_id = '$user_id'
");

// Get uploaded images
$images = $conn->query("SELECT * FROM analyzed_images WHERE user_id='$user_id' ORDER BY analyzed_at DESC");
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>My Profile</title>
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

            input, select, textarea {
                width: 100%;
                padding: 10px;
                margin-top: 5px;
                border-radius: 6px;
                border: 1px solid #ccc;
            }

            button {
                background-color: #a16bdc;
                color: white;
                border: none;
                padding: 10px 20px;
                margin-top: 20px;
                cursor: pointer;
                border-radius: 5px;
                width: 100%;
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
    <h2>My Profile</h2>

    <?php if ($updateSuccess): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb; text-align: center; margin-bottom: 20px;">
            ✅ Profile updated successfully.
        </div>
    <?php endif; ?>

    <?php if ($data): ?>
        <?php if (isset($_GET['edit'])): ?>
            <form method="POST">
                <div class="section-title">Edit Medical Info</div>
                <?php foreach ($data as $key => $value): ?>
                    <?php if (in_array($key, ['id', 'user_id', 'fullname', 'birthdate', 'gender', 'phone', 'address', 'email'])) continue; ?>
                    <div class="profile-field">
                        <label><?= ucfirst(str_replace("_", " ", $key)) ?>:</label>
                        <?php if ($key === 'skin_type'): ?>
                            <select name="skin_type" required>
                                <option <?= $value === 'Oily' ? 'selected' : '' ?>>Oily</option>
                                <option <?= $value === 'Dry' ? 'selected' : '' ?>>Dry</option>
                                <option <?= $value === 'Sensitive' ? 'selected' : '' ?>>Sensitive</option>
                                <option <?= $value === 'Normal' ? 'selected' : '' ?>>Normal</option>
                                <option <?= $value === 'Combination' ? 'selected' : '' ?>>Combination</option>
                            </select>
                        <?php elseif ($key === 'skin_concern'): ?>
                            <select name="skin_concern" required>
                                <option <?= $value === 'Acne' ? 'selected' : '' ?>>Acne</option>
                                <option <?= $value === 'Eczema' ? 'selected' : '' ?>>Eczema</option>
                                <option <?= $value === 'Psoriasis' ? 'selected' : '' ?>>Psoriasis</option>
                                <option <?= $value === 'Discoloration' ? 'selected' : '' ?>>Discoloration</option>
                                <option <?= $value === 'Wrinkles' ? 'selected' : '' ?>>Wrinkles</option>
                                <option <?= $value === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        <?php elseif ($key === 'contact_method'): ?>
                            <select name="contact_method" required>
                                <option <?= $value === 'Email' ? 'selected' : '' ?>>Email</option>
                                <option <?= $value === 'Phone' ? 'selected' : '' ?>>Phone</option>
                            </select>
                        <?php else: ?>
                            <textarea name="<?= $key ?>" required><?= htmlspecialchars($value) ?></textarea>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <button type="submit" name="update">Update Profile</button>
            </form>
        <?php else: ?>
            <div class="accordion-header">Personal Info <span class="arrow">▶</span></div>
            <div class="accordion-content">
                <?php foreach ($data as $key => $value): ?>
                    <?php if (in_array($key, ['email', 'fullname', 'birthdate', 'gender', 'phone', 'address'])): ?>
                        <div class="profile-field">
                            <label><?= ucfirst(str_replace("_", " ", $key)) ?>:</label>
                            <input type="text" class="readonly" value="<?= htmlspecialchars($value) ?>" readonly>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="accordion-header">Medical Info <span class="arrow">▶</span></div>
            <div class="accordion-content">
                <?php foreach ($data as $key => $value): ?>
                    <?php if (!in_array($key, ['email', 'fullname', 'birthdate', 'gender', 'phone', 'address', 'id', 'user_id'])): ?>
                        <div class="profile-field">
                            <label><?= ucfirst(str_replace("_", " ", $key)) ?>:</label>
                            <input type="text" class="readonly" value="<?= htmlspecialchars($value) ?>" readonly>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <!-- Appointments -->
            <div class="accordion-header">My Appointments <span class="arrow">▶</span></div>
            <div class="accordion-content">
                <?php while ($row = $appointments->fetch_assoc()): ?>
                    <div style="margin-bottom: 15px;">
                        <strong>Doctor:</strong> <?= htmlspecialchars($row['doctor_name']) ?><br>
                        <strong>Status:</strong> <?= htmlspecialchars($row['status']) ?><br>
                        <strong>Note:</strong> <?= htmlspecialchars($row['note']) ?><br>
                        <strong>Diagnosis:</strong> <?= htmlspecialchars($row['diagnosis']) ?><br>
                        <hr>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Uploaded Images -->
            <div class="accordion-header">My Uploaded Images <span class="arrow">▶</span></div>
            <div class="accordion-content">
                <?php while ($img = $images->fetch_assoc()): ?>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="<?= htmlspecialchars($img['image_path']) ?>" class="img-thumbnail"><br>
                        <strong>Condition:</strong> <?= htmlspecialchars($img['condition']) ?><br>
                        <strong>Confidence:</strong> <?= htmlspecialchars($img['confidence']) ?>%<br>
                        <strong>Date:</strong> <?= htmlspecialchars($img['analyzed_at']) ?><br>
                        <form method="POST" action="delete_image.php" onsubmit="return confirm('Delete this image?');">
                            <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                        <hr>
                    </div>
                <?php endwhile; ?>
            </div>

            <form method="GET">
                <button type="submit" name="edit" value="1">Edit Profile</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <form method="POST">
            <div class="section-title">Personal Info</div>
            <label>Full Name:</label>
            <input type="text" name="fullname" required>
            <label>Date of Birth:</label>
            <input type="date" name="birthdate" required>
            <label>Gender:</label>
            <select name="gender" required>
                <option value="">Select</option>
                <option>Male</option>
                <option>Female</option>
            </select>
            <label>Phone Number:</label>
            <input type="text" name="phone" required>
            <label>Address:</label>
            <input type="text" name="address" required>

            <div class="section-title">Medical Info</div>
            <label>Skin Type:</label>
            <select name="skin_type" required>
                <option value="">Select</option>
                <option>Oily</option>
                <option>Dry</option>
                <option>Combination</option>
                <option>Sensitive</option>
                <option>Normal</option>
            </select>
            <label>Skin Concern:</label>
            <select name="skin_concern" required>
                <option value="">Select</option>
                <option>Acne</option>
                <option>Eczema</option>
                <option>Psoriasis</option>
                <option>Discoloration</option>
                <option>Wrinkles</option>
                <option>Other</option>
            </select>
            <label>Allergies:</label>
            <textarea name="allergies"></textarea>
            <label>Current Medication:</label>
            <textarea name="medications"></textarea>
            <label>Treatment History:</label>
            <textarea name="treatment_history"></textarea>
            <label>Family History:</label>
            <textarea name="family_history"></textarea>
            <label>Preferred Contact Method:</label>
            <select name="contact_method" required>
                <option value="">Select</option>
                <option>Email</option>
                <option>Phone</option>
            </select>

            <button type="submit">Save Profile</button>
        </form>
    <?php endif; ?>
</div>
</main>

<footer>
    <p>© 2025 Derma Clinic. All rights reserved.</p>
    <p>Contact: info@dermaclinic.com | +1 (123) 456-7890</p>
</footer>
</body>
</html>

