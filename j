<?php
// Database connection settings
$servername = "localhost";  // Your database host
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "crime_reporting_system"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query for the required statistics
$crimeStatusQuery = "SELECT CRM_STATUS, COUNT(*) AS total FROM crime GROUP BY CRM_STATUS";
$crimeCategoryQuery = "SELECT crime_category.CATEGORY_DESC, COUNT(*) AS total 
                       FROM crime 
                       JOIN crime_category ON crime.CRM_CATEGORY_ID = crime_category.CRM_CATEGORY_ID
                       GROUP BY crime_category.CATEGORY_DESC";
$cityQuery = "SELECT CRM_CITY, COUNT(*) AS total FROM crime GROUP BY CRM_CITY";
$totalCrimesLast24HoursQuery = "SELECT COUNT(*) AS total FROM crime WHERE DATE_TIME > NOW() - INTERVAL 1 DAY";
$topStatesQuery = "SELECT CRM_STATE, COUNT(*) AS total FROM crime GROUP BY CRM_STATE ORDER BY total DESC LIMIT 5";
$lowestStatesQuery = "SELECT CRM_STATE, COUNT(*) AS total FROM crime GROUP BY CRM_STATE ORDER BY total ASC LIMIT 5";
$resolvedCrimesQuery = "SELECT CRM_STATE, COUNT(*) AS total FROM crime WHERE CRM_STATUS = 'Resolved' GROUP BY CRM_STATE ORDER BY total DESC LIMIT 5";
$pendingCrimesQuery = "SELECT CRM_STATE, COUNT(*) AS total FROM crime WHERE CRM_STATUS = 'Pending' GROUP BY CRM_STATE ORDER BY total ASC LIMIT 5";

// Execute the queries
$crimeStatusResult = mysqli_query($conn, $crimeStatusQuery);
$crimeCategoryResult = mysqli_query($conn, $crimeCategoryQuery);
$cityResult = mysqli_query($conn, $cityQuery);
$totalCrimesLast24HoursResult = mysqli_query($conn, $totalCrimesLast24HoursQuery);
$topStatesResult = mysqli_query($conn, $topStatesQuery);
$lowestStatesResult = mysqli_query($conn, $lowestStatesQuery);
$resolvedCrimesResult = mysqli_query($conn, $resolvedCrimesQuery);
$pendingCrimesResult = mysqli_query($conn, $pendingCrimesQuery);

// Fetch the data for crime status
$crimeStatusData = [];
while ($row = mysqli_fetch_assoc($crimeStatusResult)) {
    $crimeStatusData[] = $row;
}

// Fetch the data for crime categories
$crimeCategoryData = [];
while ($row = mysqli_fetch_assoc($crimeCategoryResult)) {
    $crimeCategoryData[] = $row;
}

// Fetch the data for crime by city
$cityData = [];
while ($row = mysqli_fetch_assoc($cityResult)) {
    $cityData[] = $row;
}

// Fetch the total number of crimes in the last 24 hours
$totalCrimesLast24HoursData = mysqli_fetch_assoc($totalCrimesLast24HoursResult);

// Fetch the top 5 states with the highest number of crimes
$topStatesData = [];
while ($row = mysqli_fetch_assoc($topStatesResult)) {
    $topStatesData[] = $row;
}

// Fetch the top 5 states with the lowest number of crimes
$lowestStatesData = [];
while ($row = mysqli_fetch_assoc($lowestStatesResult)) {
    $lowestStatesData[] = $row;
}

// Fetch the top 5 states with the highest number of resolved crimes
$resolvedCrimesData = [];
while ($row = mysqli_fetch_assoc($resolvedCrimesResult)) {
    $resolvedCrimesData[] = $row;
}

// Fetch the top 5 states with the lowest number of pending crimes
$pendingCrimesData = [];
while ($row = mysqli_fetch_assoc($pendingCrimesResult)) {
    $pendingCrimesData[] = $row;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Reporting System - Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

/* Navbar styling */
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

/* Statistics container */
.container {
    padding: 30px;
    background-color: #f4f4f4;
    text-align: center;
    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAIAAACRXR/mAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAABnSURBVHja7M5RDYAwDEXRDgmvEocnlrQS2SwUFST9uEfBGWs9c97nbGtDcquqiKhOImLs/UpuzVzWEi1atGjRokWLFi1atGjRokWLFi1atGjRokWLFi1af7Ukz8xWp8z8AAAA//8DAJ4LoEAAlL1nAAAAAElFTkSuQmCC") repeat 0 0;
    animation: bg-scrolling-reverse 0.92s infinite linear;
}

/* Title styling */
h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 30px;
    color: #333;
}

/* Stat containers */
.stat-container, .table-container {
    margin-bottom: 40px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.chart-container {
    
    margin-bottom: 40px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.stat-container h2, .chart-container h2, .table-container h2 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 20px;
    color: #333;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

table th {
    background-color: #333;
    color: #fff;
    font-weight: 800;
}

table td {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}

/* Footer styling */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: #fff;
    margin-top: 50px;
    font-weight: 800;
    position: fixed; bottom: 0; width: 100%;
}

/* Button styles (if needed for other parts) */
.btn-view-more, .btn-login, .btn-emergency {
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

.btn-view-more:hover, .btn-login:hover, .btn-emergency:hover {
    background-color: #0a0909;
}

.b{
    width: 200px;
    color: white;
    background-color: black;
    padding: 10px;
    text-decoration: none;
    font-weight: 800;
}


    </style>
</head>
<body>
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


    <div class="container">
        <h1>Crime Reporting System - Statistics</h1>

        <!-- Total Crimes in Last 24 Hours -->
        <div class="stat-container">
            <h2>Total Crimes in Last 24 Hours</h2>
            <p><?php echo $totalCrimesLast24HoursData['total']; ?> crimes reported</p>
        </div>

        <!-- Crime Status Chart -->
        <div class="chart-container">
            <h2>Crime Status</h2>
            <canvas id="crimeStatusChart"></canvas>
        </div>

        <!-- Crime Category Chart -->
        <div class="chart-container">
            <h2>Crime Categories</h2>
            <canvas id="crimeCategoryChart"></canvas>
        </div>

        <!-- Crime by City Table -->
        <div class="table-container">
            <h2>Crimes by City</h2>
            <table>
                <thead>
                    <tr>
                        <th>City</th>
                        <th>Total Crimes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cityData as $city) { ?>
                        <tr>
                            <td><?php echo $city['CRM_CITY']; ?></td>
                            <td><?php echo $city['total']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Top 5 States with Highest Number of Crimes -->
        <div class="table-container">
            <h2>Top 5 States with Highest Number of Crimes</h2>
            <table>
                <thead>
                    <tr>
                        <th>State</th>
                        <th>Total Crimes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topStatesData as $state) { ?>
                        <tr>
                            <td><?php echo $state['CRM_STATE']; ?></td>
                            <td><?php echo $state['total']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Top 5 States with Lowest Number of Crimes -->
        <div class="table-container">
            <h2>Top 5 States with Lowest Number of Crimes</h2>
            <table>
                <thead>
                    <tr>
                        <th>State</th>
                        <th>Total Crimes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lowestStatesData as $state) { ?>
                        <tr>
                            <td><?php echo $state['CRM_STATE']; ?></td>
                            <td><?php echo $state['total']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Top 5 States with Highest Number of Resolved Crimes -->
        <div class="table-container">
            <h2>Top 5 States with Highest Number of Resolved Crimes</h2>
            <table>
                <thead>
                    <tr>
                        <th>State</th>
                        <th>Total Resolved Crimes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resolvedCrimesData as $state) { ?>
                        <tr>
                            <td><?php echo $state['CRM_STATE']; ?></td>
                            <td><?php echo $state['total']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Top 5 States with Lowest Number of Pending Crimes -->
        <div class="table-container">
            <h2>Top 5 States with Lowest Number of Pending Crimes</h2>
            <table>
                <thead>
                    <tr>
                        <th>State</th>
                        <th>Total Pending Crimes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingCrimesData as $state) { ?>
                        <tr>
                            <td><?php echo $state['CRM_STATE']; ?></td>
                            <td><?php echo $state['total']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <a href="generate_report.php" class="b">Generate Report</a>

    </div>

    <script>
        // Crime Status Chart
        const crimeStatusCtx = document.getElementById('crimeStatusChart').getContext('2d');
        const crimeStatusChart = new Chart(crimeStatusCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($crimeStatusData, 'CRM_STATUS')); ?>,
                datasets: [{
                    label: 'Crime Status Distribution',
                    data: <?php echo json_encode(array_column($crimeStatusData, 'total')); ?>,
                    backgroundColor: ['#ff9999', '#66b3ff', '#99ff99'],
                    borderColor: ['#fff', '#fff', '#fff'],
                    borderWidth: 1
                }]
            }
        });

        // Crime Category Chart
        const crimeCategoryCtx = document.getElementById('crimeCategoryChart').getContext('2d');
        const crimeCategoryChart = new Chart(crimeCategoryCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($crimeCategoryData, 'CATEGORY_DESC')); ?>,
                datasets: [{
                    label: 'Crime Categories',
                    data: <?php echo json_encode(array_column($crimeCategoryData, 'total')); ?>,
                    backgroundColor: '#ff6666',
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            }
        });
    </script>
    <footer>
        This project is Developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narender Pal Sir.
    </footer>
</body>
</html>
