<!DOCTYPE html>
<html>
<head>
    <title>Signup - Crime Reporting System</title>
    <style>
        /* CSS Styles remain unchanged */
        @import url('https://fonts.googleapis.com/css?family=Exo:100');

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

        .navbar .brand, .navbar .brand img {
            cursor: pointer;
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

        .signup-container {
            width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .signup-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .signup-container input, .signup-container textarea, .signup-container select, .signup-container button {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .signup-container button {
            background-color: #333;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .signup-container button:hover {
            background-color: #0a0909;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            margin-top: 190px;
            font-weight: bold;
            position: fixed; bottom: 0; width: 100%;
        }

        p {
            font-weight: bold;
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

    <div class="signup-container">
        <h1>Signup</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="text" name="mobile" placeholder="Mobile Number" required><br>
            <textarea name="address" placeholder="Address" required></textarea><br>
            
            <button type="submit" name="signup">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <footer>
        This project is made by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narender Pal Sir.
    </footer>
</body>
</html>
<?php
// Database connection (update with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$database = "crime_reporting_system";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signup'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $address = $conn->real_escape_string($_POST['address']);
    $role_id = 2; // Default role ID

    $sql = "INSERT INTO users (USER_NAME, USER_EMAIL, USER_PASSWORD, USER_MOBILE, USER_ADDRESS, ROLE_ID) 
            VALUES ('$username', '$email', '$password', '$mobile', '$address', '$role_id')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Signup successful! You can now log in.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>
