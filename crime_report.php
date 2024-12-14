<?php
session_start();
$conn = new mysqli("localhost", "root", "", "crime_reporting_system");

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['report_crime'])) {
    $crime_desc = $_POST['crime_desc'];
    $crime_place = $_POST['crime_place'];
    $crime_category_id = $_POST['crime_category_id'];
    $date_time = $_POST['date_time'];
    $user_id = $_SESSION['user_id'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $coordinates = $_POST['coordinates'];

    $stmt = $conn->prepare("INSERT INTO crime (CRM_DESC, CRM_PLACE, CRM_CATEGORY_ID, DATE_TIME, USER_ID, CRM_STATE, CRM_CITY, CRM_MAP_COORDINATES) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisisss", $crime_desc, $crime_place, $crime_category_id, $date_time, $user_id, $state, $city, $coordinates);
    if ($stmt->execute()) {
        echo "<script>alert('Crime Reported Successfully!');</script>";
    } else {
        echo "<script>alert('Error Reporting Crime!');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crime Report - Crime Reporting System</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        @import url('https://fonts.googleapis.com/css?family=Exo:100');

    body {
    margin: 0;
    font-family: 'Exo', sans-serif;
    font-weight: 800;
    color: #333;
    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAIAAACRXR/mAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAABnSURBVHja7M5RDYAwDEXRDgmvEocnlrQS2SwUFST9uEfBGWs9c97nbGtDcquqiKhOImLs/UpuzVzWEi1atGjRokWLFi1atGjRokWLFi1atGjRokWLFi1af7Ukz8xWp8z8AAAA//8DAJ4LoEAAlL1nAAAAAElFTkSuQmCC") repeat 0 0;
    animation: bg-scrolling-reverse 0.92s infinite linear;
}

@keyframes bg-scrolling-reverse {
    100% {
        background-position: 50px 50px;
    }
}

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
        }

        .navbar .brand {
            font-size: 1.5em;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .navbar .brand img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar ul li {
            margin: 0 15px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            transition: color 0.3s;
        }

        .navbar ul li a:hover {
            color: #00bcd4;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        form textarea, form input, form select, form button {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-weight: bold;
        }

        form button {
            background-color: #333;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #555;
        }

        #map {
            height: 300px;
            margin: 10px auto;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            margin-top: 30px;
        }
    </style>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body onload="initMap()">
<div class="navbar">
        <div class="brand" onclick="window.location.href='index.php';">
            <img src="l.png" alt="Logo">
            Crime Management System
        </div>
        <ul>
            <li><a href="users_dashboard.php">Profile</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="crime_report.php">Report</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>
    </div>
    <h2>Crime Report</h2>
    <form method="POST">
        <textarea name="crime_desc" placeholder="Describe the crime" required></textarea><br>
        <input type="text" name="crime_place" placeholder="Crime Location" required><br>
        <select name="crime_category_id" required>
            <option value="" disabled selected>Select Crime Category</option>
            <?php
            $categories = $conn->query("SELECT CRM_CATEGORY_ID, CATEGORY_DESC FROM crime_category");
            while ($row = $categories->fetch_assoc()) {
                echo "<option value='{$row['CRM_CATEGORY_ID']}'>{$row['CATEGORY_DESC']}</option>";
            }
            ?>
        </select><br>
        <input type="datetime-local" name="date_time" required><br>
        <select name="state" required>
            <option value="" disabled selected>Select State</option>
            <?php
            $states = ["Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", "Uttarakhand", "West Bengal"];
            foreach ($states as $state) {
                echo "<option value='$state'>$state</option>";
            }
            ?>
        </select><br>
        <input type="text" name="city" placeholder="Enter City" required><br>
        <div id="map"></div>
        <input type="text" id="coordinates" name="coordinates" placeholder="Coordinates" readonly required><br>
        <button type="submit" name="report_crime">Report Crime</button>
    </form>
    <footer>
    <p>Developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narendra Pal sir.</p>
    </footer>
    <script>
        function initMap() {
            var map = L.map('map').setView([28.6448, 77.216721], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            var marker = L.marker([28.6448, 77.216721]).addTo(map);
            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;
                marker.setLatLng(e.latlng);
                document.getElementById('coordinates').value = lat + ',' + lng;
            });
        }
    </script>
</body>
</html>
