<?php
session_start();
$conn = new mysqli("localhost", "root", "", "crime_reporting_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Query to fetch the user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE USER_EMAIL = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['USER_PASSWORD'])) {
        // Store session data
        $_SESSION['user_id'] = $user['USER_ID'];
        $_SESSION['role_id'] = $user['ROLE_ID'];

        // Redirect based on role
        if ($user['ROLE_ID'] == 1) {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: users_dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid Email or Password!');</script>";
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Crime Reporting System</title>
    <style>
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

        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .login-container input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-container button {
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
c
        }

        .login-container button:hover {
            background-color: #0a0909;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            margin-top: 215Px;
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

    <div class="login-container">
        <h1>Login</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>

    <footer>
        This project is Developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narender Pal Sir.
    </footer>
</body>
</html>