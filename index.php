<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Management System</title>
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

        .btn-emergency {
            display: inline-block;
            margin-top: 20px;
            margin-left: 10px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            background-color: #d9534f;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
            font-weight: 700;
        }

        .btn-emergency:hover {
            background-color: #c9302c;
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

        .btn-view-more {
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

        .btn-view-more:hover {
            background-color: #0a0909;
        }

        .statistics {
            margin: 50px 20px;
            text-align: center;
        }

        .statistics h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 800;
        }

        .statistics p {
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
        }

        .navbar li {
            font-weight: 800;
        }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="brand">
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

    <div class="hero">
        <h1>Welcome to Crime Management System</h1>
        <p>Empowering citizens to report crimes and helping authorities manage them efficiently.</p>
        <a href="crime_report.php" class="btn-login">Report Crime</a>
        <a href="Emergency.php" class="btn-emergency">Emergency Button</a>
    </div>

    <div class="statistics">
        <h2>Crime Statistics</h2>
        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'crime_reporting_system');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query for statistics
        $totalCrimes = $conn->query("SELECT COUNT(*) AS total FROM crime")->fetch_assoc()['total'];
        $resolvedCrimes = $conn->query("SELECT COUNT(*) AS resolved FROM crime WHERE CRM_STATUS = 'Resolved'")->fetch_assoc()['resolved'];
        $pendingCrimes = $conn->query("SELECT COUNT(*) AS pending FROM crime WHERE CRM_STATUS = 'Pending'")->fetch_assoc()['pending'];

        echo "<p>Total Reported Crimes: $totalCrimes</p>";
        echo "<p>Resolved Crimes: $resolvedCrimes</p>";
        echo "<p>Pending Crimes: $pendingCrimes</p>";

        $conn->close();
        ?>
        <a href="statistics.php" class="btn-view-more">View More</a>

    </div>

    <div class="features">
        <h2>Features of the System</h2>
        <p>- Report crimes and provide detailed information.</p>
        <p>- Admin dashboard with a heatmap of crime density across India.</p>
        <p>- Automatic email notifications to higher authorities.</p>
        <p>- Filtering and sorting of crime reports based on various criteria.</p>
        <p>- AI chatbot to assist users.</p>
        <p>- Emergency button for women, notifying authorities and guardians with location sharing.</p>
    </div>

    <footer>
        This project is Developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narender Pal Sir.
    </footer>

</body>
</html>
