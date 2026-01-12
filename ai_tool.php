<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Skin Analysis Tool</title>
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

        .header-left {
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-family: 'Caveat', cursive;
            font-size: 32px;
            color: #8e44ad;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .motto {
            font-size: 14px;
            color: #888;
            margin-top: -5px;
        }

        .nav-links a {
            color: #0f0f11;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        .main-section {
            padding: 40px 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }

        h2 {
            color: #6a0dad;
            margin-bottom: 10px;
        }

        p.description {
            font-size: 16px;
            color: #444;
            margin-bottom: 25px;
        }

        .upload-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        input[type="file"] {
            display: block;
            margin: 20px auto;
            font-size: 16px;
        }

        button {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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

<!-- Main Section -->
<main class="main-section">
    <h2>AI-Powered Skin Condition Analyzer</h2>
    <p class="description">Upload a clear image of your skin concern. Our AI will analyze it and provide suggestions.</p>

    <div class="upload-box">
        <form action="analyze_image.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="skin_image" accept="image/*" required>
            <button type="submit">Analyze Image</button>
        </form>
    </div>
</main>

<!-- Footer -->
<footer id="about">
    &copy; 2025 Derma Clinic. All rights reserved.
</footer>
</body>
</html>
