<?php
// ربط قاعدة البيانات
include 'db_connection.php';

// استعلام للحصول على إحصائيات الأطباء
$sql = "SELECT d.name as doctor_name, COUNT(a.appointment_id) AS appointments_count 
        FROM doctors d 
        LEFT JOIN appointments a ON d.id = a.doctor_id
        GROUP BY d.id";
$result = mysqli_query($conn, $sql);
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Doctor Statistics</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background: linear-gradient(to right, #9f71a5, #ebd2f5);
                margin: 0;
                padding: 0;
            }
            .container {
                width: 80%;
                max-width: 800px;
                margin: 50px auto;
                background-color: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                text-align: center;
                color: #333;
            }
            canvas {
                width: 100%;
                height: 400px;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>Doctor Statistics</h1>
        <canvas id="doctorStatsChart"></canvas>
        <script>
            const ctx = document.getElementById('doctorStatsChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Number of Appointments',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // عرض البيانات في الرسم البياني
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "chart.data.labels.push('" . $row['doctor_name'] . "');";
                echo "chart.data.datasets[0].data.push(" . $row['appointments_count'] . ");";
            }
            ?>
            chart.update();
        </script>
    </div>
    </body>
    </html>

<?php
mysqli_close($conn);
?>