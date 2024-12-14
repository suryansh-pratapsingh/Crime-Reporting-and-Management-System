<?php
// index.php

session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "crime_reporting_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch crime data by state
$sql = "SELECT CRM_STATE, COUNT(*) AS crime_count FROM crime WHERE CRM_STATE IS NOT NULL GROUP BY CRM_STATE";
$result = $conn->query($sql);

$state_data = [];
while ($row = $result->fetch_assoc()) {
    $state_data[$row['CRM_STATE']] = $row['crime_count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Management System</title>
    <!-- Leaflet.js CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-choropleth@1.0.0/choropleth.js"></script>
    <script src="https://unpkg.com/leaflet-heatmap.js"></script>

    <!-- Custom Styling for the Page -->
    <style>
        /* Import Google Font */
        @import url('https://fonts.googleapis.com/css?family=Exo:100');

        /* Background settings */
        body {
            margin: 0;
            font-family: 'Exo', sans-serif;
            color: #333;
            background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAIAAACRXR/mAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAABnSURBVHja7M5RDYAwDEXRDgmvEocnlrQS2SwUFST9uEfBGWs9c97nbGtDcquqiKhOImLs/UpuzVzWEi1atGjRokWLFi1atGjRokWLFi1atGjRokWLFi1af7Ukz8xWp8z8AAAA//8DAJ4LoEAAlL1nAAAAAElFTkSuQmCC") repeat 0 0;
            animation: bg-scrolling-reverse 0.92s infinite linear;
        }

        @keyframes bg-scrolling-reverse {
            100% { background-position: 50px 50px; }
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

        .hero {
            text-align: center;
            margin-top: 50px;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 20px;
            font-weight: 800;
        }

        .hero p {
            font-size: 1.5rem;
            font-weight: bold;
            font-weight: 700;
        }

        .btn-login {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            background-color: #333;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
            font-weight: 700;
        }

        .btn-login:hover {
            background-color: #0a0909;
        }

        .features {
            margin: 50px 20px;
            text-align: center;
            color: #333;
        }

        .features h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 800;
        }

        .features p {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            margin-top: 50px;
            font-weight: 800;
            position: fixed; bottom: 0; width: 100%;
        }

        .navbar li{
            font-weight: 800
        }

        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navbar Section -->
    <div class="navbar">
        <a href="index.php" class="brand">
            <img src="logo.png" alt="Logo">Crime Management System
        </a>
        <ul>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to Crime Management System</h1>
        <p>Explore crime density across India on the map</p>
    </div>

    <!-- Features Section -->
    <div class="features">
        <h2>Crime Map</h2>
        <p>This map highlights the crime density in various states of India based on real data.</p>
        <!-- Map Section -->
        <div id="map"></div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>© 2024 Crime Management System. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Leaflet Map and GeoJSON integration -->
    <script>
        // Initialize the Leaflet map
        var map = L.map('map').setView([20.5937, 78.9629], 5); // Center of India

        // Add a tile layer to the map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // GeoJSON data for Indian states (Replace with the actual GeoJSON data file path)
        var indiaStatesGeoJson = <?php echo file_get_contents('india_states.geojson'); ?>;

        // Crime data for each state (fetched from the database)
        var crimeData = <?php echo json_encode($state_data); ?>;

        // Function to get the crime count for each state
        function getCrimeCount(stateName) {
            return crimeData[stateName] || 0;
        }

        // Function to style the states based on crime density
        function style(feature) {
            var crimeCount = getCrimeCount(feature.properties.name);
            var fillColor = crimeCount > 100 ? '#800026' : crimeCount > 50 ? '#BD0026' : '#E31A1C'; // Adjust color ranges
            return {
                fillColor: fillColor,
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            };
        }

        // Add GeoJSON layer to the map
        L.geoJSON(indiaStatesGeoJson, {
            style: style
        }).addTo(map);
    </script>

</body>
</html>
