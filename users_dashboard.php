<?php
session_start();
$conn = new mysqli("localhost", "root", "", "crime_reporting_system");

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle report editing
if (isset($_POST['edit_report'])) {
    $crm_id = $_POST['crm_id'];
    $crm_desc = $_POST['crm_desc'];
    $crm_place = $_POST['crm_place'];
    $crm_category = $_POST['crm_category'];
    $crm_status = $_POST['crm_status'];
    $crm_state = $_POST['crm_state'];
    $crm_city = $_POST['crm_city'];
    $crm_coordinates = $_POST['crm_coordinates'];

    $update_query = "UPDATE crime SET CRM_DESC='$crm_desc', CRM_PLACE='$crm_place', CRM_CATEGORY_ID='$crm_category', CRM_STATUS='$crm_status', CRM_STATE='$crm_state', CRM_CITY='$crm_city', CRM_MAP_COORDINATES='$crm_coordinates' WHERE CRM_ID='$crm_id'";
    if ($conn->query($update_query)) {
        $message = "Report updated successfully.";
    } else {
        $message = "Failed to update the report.";
    }
}

$result = $conn->query("SELECT c.CRM_ID, c.CRM_DESC, c.CRM_PLACE, cc.CATEGORY_DESC, c.DATE_TIME, c.CRM_STATUS, c.CRM_STATE, c.CRM_CITY, c.CRM_MAP_COORDINATES
    FROM crime c 
    LEFT JOIN crime_category cc ON c.CRM_CATEGORY_ID = cc.CRM_CATEGORY_ID 
    WHERE c.USER_ID = $user_id");

$categories_result = $conn->query("SELECT * FROM crime_category");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Crime Reporting System</title>
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
        h2, h3 {
            text-align: center;
            color: #333;
        }
        form {
    width: 300px;  /* Reduced form width */
    margin: 20px auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

form textarea, form input, form select, form button {
    width: 100%;  /* Ensure elements inside the form use the full width of the form */
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-weight: 800;
}

form button {
    background-color:  #333;
    color: white;
    font-weight: 800;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;  /* Make button take the full width of the form */
}

form button:hover {
    background-color: #0a0909;
}

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            font-weight: 800;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            margin-top: 490px;
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

<h2>User Dashboard</h2>
<h3>Your Reported Crimes</h3>

<!-- Display message after report update -->
<?php if (isset($message)) { ?>
    <div style="text-align: center; color: green;"><?= $message ?></div>
<?php } ?>

<table>
    <tr>
        <th>Description</th>
        <th>Location</th>
        <th>Category</th>
        <th>Date & Time</th>
        <th>Status</th>
        <th>State</th>
        <th>City</th>
        <th>Coordinates</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['CRM_DESC'] ?></td>
            <td><?= $row['CRM_PLACE'] ?></td>
            <td><?= $row['CATEGORY_DESC'] ?></td>
            <td><?= $row['DATE_TIME'] ?></td>
            <td><?= $row['CRM_STATUS'] ?></td>
            <td><?= $row['CRM_STATE'] ?></td>
            <td><?= $row['CRM_CITY'] ?></td>
            <td><?= $row['CRM_MAP_COORDINATES'] ?></td>
            <td>
                <form method="post" action="">
                    <input type="hidden" name="crm_id" value="<?= $row['CRM_ID'] ?>">
                    <input type="submit" name="edit" value="Edit" />
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
// Display the edit form if the "Edit" button is clicked
if (isset($_POST['edit'])) {
    $edit_crm_id = $_POST['crm_id'];
    $edit_result = $conn->query("SELECT * FROM crime WHERE CRM_ID = $edit_crm_id");
    $edit_row = $edit_result->fetch_assoc();
    ?>
    <h3>Edit Report</h3>
    <form method="post" action="">
        <input type="hidden" name="crm_id" value="<?= $edit_row['CRM_ID'] ?>">
        <textarea name="crm_desc" required><?= $edit_row['CRM_DESC'] ?></textarea>
        <input type="text" name="crm_place" required value="<?= $edit_row['CRM_PLACE'] ?>">
        <select name="crm_category">
            <?php while ($category = $categories_result->fetch_assoc()) { ?>
                <option value="<?= $category['CRM_CATEGORY_ID'] ?>" <?= ($category['CRM_CATEGORY_ID'] == $edit_row['CRM_CATEGORY_ID']) ? 'selected' : '' ?>>
                    <?= $category['CATEGORY_DESC'] ?>
                </option>
            <?php } ?>
        </select>
        <input type="text" name="crm_status" required value="<?= $edit_row['CRM_STATUS'] ?>">
        <input type="text" name="crm_state" required value="<?= $edit_row['CRM_STATE'] ?>">
        <input type="text" name="crm_city" required value="<?= $edit_row['CRM_CITY'] ?>">
        <input type="text" name="crm_coordinates" required value="<?= $edit_row['CRM_MAP_COORDINATES'] ?>">
        <button type="submit" name="edit_report">Update Report</button>
    </form>
<?php } ?>

<footer>
    <p>Developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narendra Pal sir.</p>
</footer>

</body>
</html>
