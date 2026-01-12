<?php
// ربط قاعدة البيانات
include 'db_connection.php';

// استعلام لعرض الأطباء
$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Doctors List</title>
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background: linear-gradient(to right, #9f71a5, #ebd2f5);
                margin: 0;
                padding: 0;
            }
            .container {
                width: 80%;
                max-width: 1200px;
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
            input {
                padding: 10px;
                width: 100%;
                margin-bottom: 20px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 15px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #9f71a5;
                color: white;
            }
            button {
                padding: 8px 15px;
                background-color: #9f71a5;
                border: none;
                color: white;
                cursor: pointer;
                border-radius: 5px;
            }
            button:hover {
                background-color: #7e4f7f;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>Doctors List</h1>
        <input type="text" id="search" placeholder="Search doctors..." onkeyup="searchDoctors()">
        <table>
            <thead>
            <tr>
                <th>Doctor Name</th>
                <th>Specialty</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="doctorTable">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['specialty']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>
        <button onclick='viewDoctor(" . $row['id'] . ")'>View</button>
        <button onclick='editDoctor(" . $row['id'] . ")'>Edit</button>
        <button onclick='deleteDoctor(" . $row['id'] . ")'>Delete</button>
    </td>";
                echo "</tr>";
            }

                ?>
            </tbody>
        </table>
    </div>

    <script>
        // البحث عن الأطباء في الجدول
        function searchDoctors() {
            let input = document.getElementById('search');
            let filter = input.value.toLowerCase();
            let table = document.getElementById('doctorTable');
            let rows = table.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length - 1; j++) {
                    if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        }

        // وظائف عرض وتعديل الطبيب
        function viewDoctor(doctorId) {
            window.location.href = 'view_doctor.php?id=' + doctorId;
        }

        function editDoctor(doctorId) {
            window.location.href = 'edit_doctor.php?id=' + doctorId;
        }
        function deleteDoctor(doctorId) {
            if (confirm("Are you sure you want to delete this doctor?")) {
                window.location.href = 'delete_doctor.php?id=' + doctorId;
            }
        }

    </script>
    </body>
    </html>

<?php
mysqli_close($conn);
?>