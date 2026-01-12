<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #9f71a5, #ebd2f5);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: white;
            margin-top: 40px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .sub-header {
            font-size: 16px;
            color: #eee;
            margin-bottom: 40px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 0 40px;
            width: 100%;
            max-width: 1200px;
        }

        .centered-row {
            display: flex;
            justify-content: center;
            gap: 30px; /* same as grid */
            margin-top: 30px; /* same as grid vertical gap */
            flex-wrap: wrap;
        }


        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            color: white;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
            cursor: pointer;
            width: 250px;
        }

        .glass-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 40px rgba(0,0,0,0.2);
        }

        .glass-card i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #fff;
        }

        .glass-card h3 {
            margin: 10px 0;
            font-size: 20px;
        }

        .glass-card p {
            font-size: 14px;
            opacity: 0.9;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>

<div class="header">Admin Dashboard</div>
<div class="sub-header">Welcome to the Dermatology AI Clinic – Admin Panel</div>

<!-- Top Row: 3 Cards -->
<div class="grid-container">
    <a href="add_doctor.php" class="glass-card">
        <i class="fas fa-user-md"></i>
        <h3>Add Doctor</h3>
        <p>Create new doctor profiles in the system.</p>
    </a>
    <a href="manage_appointments.php" class="glass-card">
        <i class="fas fa-calendar-check"></i>
        <h3>Manage Appointments</h3>
        <p>View, update, or cancel appointments.</p>
    </a>
    <a href="doctor_stats.php" class="glass-card">
        <i class="fas fa-chart-bar"></i>
        <h3>Doctor Statistics</h3>
        <p>Track performance and activity of all doctors.</p>
    </a>
</div>

<!-- Bottom Row: 2 Cards Centered -->
<div class="centered-row">
    <a href="doctors_list.php" class="glass-card">
        <i class="fas fa-users"></i>
        <h3>Doctors List</h3>
        <p>Browse and manage registered doctors.</p>
    </a>

    <a href="reset_appointments.php" class="glass-card">
        <i class="fas fa-sync-alt"></i>
        <h3>Reset Appointments</h3>
        <p>Clear and reset the booking system.</p>
    </a>
</div>


</body>
</html>
