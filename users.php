<?php
session_start();
if ($_SESSION['role_id'] != 1) {
    header("Location: signup_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "crime_reporting_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle role update
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];

    // Check if the role exists in the roles table
    $role_check = $conn->query("SELECT 1 FROM roles WHERE ROLE_ID = $new_role");
    if ($role_check && $role_check->num_rows > 0) {
        // Update the user's role
        if ($conn->query("UPDATE users SET role_id = $new_role WHERE USER_ID = $user_id")) {
            echo "<script>alert('User role updated successfully.');</script>";
        } else {
            echo "<script>alert('Error updating user role.');</script>";
        }
    } else {
        echo "<script>alert('Invalid role selected. Please contact the administrator.');</script>";
    }
}

// Fetch user data with crime count
$users = $conn->query(
    "SELECT users.USER_ID, users.USER_NAME, users.USER_MOBILE, users.ROLE_ID, COUNT(crime.CRM_ID) AS crime_count 
    FROM users 
    LEFT JOIN crime ON users.USER_ID = crime.USER_ID 
    GROUP BY users.USER_ID"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .button {
            padding: 5px 10px;
            color: white;
            background-color: blue;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .button.red {
            background-color: red;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            margin-top: 290px;
            font-weight: 800;
            position: fixed; bottom: 0; width: 100%;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-top: 20px;
            font-weight: 800;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="brand"  onclick="window.location.href='index.php';">
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

    <h1>User Management</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Mobile</th>
            <th>Role</th>
            <th>Crimes Reported</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $users->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['USER_NAME']); ?></td>
                <td><?php echo htmlspecialchars($row['USER_MOBILE']); ?></td>
                <td><?php echo $row['ROLE_ID'] == 1 ? 'Admin' : 'User'; ?></td>
                <td><?php echo $row['crime_count']; ?></td>
                <td>
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="user_id" value="<?php echo $row['USER_ID']; ?>">
                        <input type="hidden" name="new_role" value="<?php echo $row['ROLE_ID'] == 1 ? 2 : 1; ?>">
                        <button type="submit" name="update_role" class="button <?php echo $row['ROLE_ID'] == 1 ? 'red' : ''; ?>">
                            <?php echo $row['ROLE_ID'] == 1 ? 'Revoke Admin' : 'Make Admin'; ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <footer>
        This project is Developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narender Pal Sir.
    </footer>

</body>
</html>
