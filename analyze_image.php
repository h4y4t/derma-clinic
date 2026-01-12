<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$result = null;
$confidence = null;
$destinationPath = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["skin_image"])) {
    // Save image to permanent location
    $imageTmpPath = $_FILES["skin_image"]["tmp_name"];
    $imageName = basename($_FILES["skin_image"]["name"]);
    $uploadDir = "uploads/";
    $destinationPath = $uploadDir . time() . "_" . $imageName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    move_uploaded_file($imageTmpPath, $destinationPath);

    // Send image to AI server
    $curl = curl_init();
    $postFields = [
        'image' => new CURLFile($destinationPath)
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://127.0.0.1:5000/analyze",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        $result = "cURL error: " . curl_error($curl);
        $confidence = "N/A";
    } else {
        $decoded = json_decode($response, true);
        if (isset($decoded["predictions"]) && count($decoded["predictions"]) > 0) {
            $topPrediction = $decoded["predictions"][0];
            $result = $topPrediction["condition"];
            $confidence = $topPrediction["confidence"];
        } else {
            $result = "Invalid response from AI server.";
            $confidence = "N/A";
        }
    }

    curl_close($curl);

    // Save to database
    if ($result && $confidence !== null) {
        $user_id = $_SESSION['user_id'];
        $conn = new mysqli("localhost", "root", "", "derma_clinic");

        $stmt = $conn->prepare("INSERT INTO analyzed_images (user_id, image_path, `condition`, confidence, analyzed_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $user_id, $destinationPath, $result, $confidence);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Skin Analyzer Result</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #ffe4ff;
        }
        .navbar {
            display: flex;
            align-items: center;
            padding: 15px 30px;
            background: linear-gradient(135deg, #e6ccff, #d8b4e2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            gap: 40px;
            flex-wrap: wrap;
        }
        .logo {
            font-family: 'Caveat', cursive;
            font-size: 32px;
            color: #8e44ad;
            margin-bottom: 5px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }
        .motto {
            font-size: 14px;
            color: #888;
        }
        .nav-links a {
            color: #0f0f11;
            text-decoration: none;
            margin-left: 20px;
        }
        .main-section {
            padding: 40px 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }
        h2 {
            color: #6a0dad;
            margin-bottom: 20px;
        }
        .result-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .result-box p {
            font-size: 18px;
            margin: 10px 0;
        }
        .result-box img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        button {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #732d91;
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
</head>
<body>

<!-- Main Content -->
<div class="main-section">
    <h2>AI Skin Condition Result</h2>
    <div class="result-box">
        <?php if ($result): ?>
            <?php if ($destinationPath): ?>
                <img src="<?= htmlspecialchars($destinationPath) ?>" alt="Uploaded Image">
            <?php endif; ?>
            <p><strong>Condition:</strong> <?= htmlspecialchars($result) ?></p>
            <p><strong>Confidence:</strong> <?= htmlspecialchars($confidence) ?>%</p>
        <?php else: ?>
            <p>No image analyzed. Please <a href="ai_tool.php">upload an image</a>.</p>
        <?php endif; ?>
        <a href="ai_tool.php"><button>Try Another Image</button></a>
    </div>
</div>

<!-- Footer -->
<footer id="about">
    &copy; 2025 Derma Clinic. All rights reserved.
</footer>
</body>
</html>
