
<?php
session_start();
if ($_SESSION['role_id'] != 1) {
    header("Location: signup_login.php");
}

$conn = new mysqli("localhost", "root", "", "crime_reporting_system");

// Update Crime Status
if (isset($_POST['update_status'])) {
    $status = $_POST['crm_status'];
    $crm_id = $_POST['crm_id'];
    $conn->query("UPDATE crime SET CRM_STATUS='$status' WHERE CRM_ID=$crm_id");
}

// Delete Crime
if (isset($_POST['delete_crime'])) {
    $crm_id = $_POST['crm_id'];
    $conn->query("DELETE FROM crime WHERE CRM_ID=$crm_id");
}

// Sorting and Filtering
$order_by = "CRM_ID"; // Default sorting column
$order_dir = "ASC"; // Default sorting direction
$where_clauses = [];

if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['CATEGORY_DESC', 'CRM_CITY', 'CRM_STATE', 'CRM_STATUS', 'DATE_TIME'])) {
    $order_by = $_GET['sort_by'];
}

if (isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC'])) {
    $order_dir = $_GET['order'];
}

if (!empty($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);
    $where_clauses[] = "crime_category.CATEGORY_DESC = '$category'";
}

if (!empty($_GET['city'])) {
    $city = $conn->real_escape_string($_GET['city']);
    $where_clauses[] = "crime.CRM_CITY = '$city'";
}

if (!empty($_GET['state'])) {
    $state = $conn->real_escape_string($_GET['state']);
    $where_clauses[] = "crime.CRM_STATE = '$state'";
}

if (!empty($_GET['status'])) {
    $status = $conn->real_escape_string($_GET['status']);
    $where_clauses[] = "crime.CRM_STATUS = '$status'";
}

if (!empty($_GET['date_time'])) {
    $date_time = $conn->real_escape_string($_GET['date_time']);
    $where_clauses[] = "crime.DATE_TIME = '$date_time'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "
    SELECT crime.*, crime_category.CATEGORY_DESC, users.USER_NAME, users.USER_EMAIL
    FROM crime
    LEFT JOIN crime_category ON crime.CRM_CATEGORY_ID = crime_category.CRM_CATEGORY_ID
    LEFT JOIN users ON crime.USER_ID = users.USER_ID
    $where_sql
    ORDER BY $order_by $order_dir
";

$crimes = $conn->query($query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Exo:100');

        body {
            margin: 0;
            font-family: 'Exo', sans-serif;
            color: #333;
            background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAIAAACRXR/mAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAABnSURBVHja7M5RDYAwDEXRDgmvEocnlrQS2SwUFST9uEfBGWs9c97nbGtDcquqiKhOImLs/UpuzVzWEi1atGjRokWLFi1atGjRokWLFi1atGjRokWLFi1af7Ukz8xWp8z8AAAA//8DAJ4LoEAAlL1nAAAAAElFTkSuQmCC") repeat 0 0;
            animation: bg-scrolling-reverse 0.92s infinite linear;
            font-weight: 800;

        }

        label {
            padding: 2px;
        }

        @keyframes bg-scrolling-reverse {
            100% {
                background-position: 50px 50px;
            }
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
        }

        nav h1 {
            margin: 0;
            display: flex;
            align-items: center;
            font-weight: 800;
        }

        nav img {
            height: 40px;
            margin-right: 10px;
        }

        nav .links {
            display: flex;
            align-items: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 800;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #00bcd4;
        }

        main {
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-weight: 800;
        }

        table {
            width: 90%;
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

        form select, form button {
            padding: 5px;
            margin: 5px 0;
            font-weight: 800;
        }

        form button {
            background-color:  #333;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #0a0909;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            font-weight: 800;
            margin-top: 320px;
            position: fixed; bottom: 0; width: 100%;
        }
    </style>
</head>

<body>
<nav>
        <h1 onclick="window.location.href='index.php';">
            <img src="l.png" alt="Logo">
            Crime Reporting System
        </h1>
        <div class="links">
            <a href="generate_report.php">Generate Report</a>
            <a href="users.php">User</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <h2>Admin Dashboard</h2>
    <!-- Sorting and Filtering Form -->
    <form method="GET" action="">
        <label>Category:</label>
        <input type="text" name="category" value="<?= $_GET['category'] ?? '' ?>">

        <label>City:</label>
        <input type="text" name="city" value="<?= $_GET['city'] ?? '' ?>">

        <label>State:</label>
        <input type="text" name="state" value="<?= $_GET['state'] ?? '' ?>">

        <label>Status:</label>
        <input type="text" name="status" value="<?= $_GET['status'] ?? '' ?>">

        <label>Date Time:</label>
        <input type="text" name="date_time" value="<?= $_GET['date_time'] ?? '' ?>">

        <button type="submit">Filter</button>

        <label>Sort By:</label>
        <select name="sort_by">
            <option value="CRM_ID">ID</option>
            <option value="CATEGORY_DESC">Category</option>
            <option value="CRM_CITY">City</option>
            <option value="CRM_STATE">State</option>
            <option value="CRM_STATUS">Status</option>
            <option value="DATE_TIME">Date Time</option>
        </select>

        <label>Order:</label>
        <select name="order">
            <option value="ASC">Ascending</option>
            <option value="DESC">Descending</option>
        </select>

        <button type="submit">Sort</button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Category</th>
            <th>Place</th>
            <th>City</th>
            <th>State</th>
            <th>Status</th>
            <th>Date Time</th>
            <th>User Details</th>
            <th>Actions</th>
        </tr>
        <?php while ($crime = $crimes->fetch_assoc()) { ?>
        <tr>
            <td><?= $crime['CRM_ID'] ?></td>
            <td><?= $crime['CRM_DESC'] ?></td>
            <td><?= $crime['CATEGORY_DESC'] ?></td>
            <td><?= $crime['CRM_PLACE'] ?></td>
            <td><?= $crime['CRM_CITY'] ?></td>
            <td><?= $crime['CRM_STATE'] ?></td>
            <td><?= $crime['CRM_STATUS'] ?></td>
            <td><?= $crime['DATE_TIME'] ?></td>
            <td>
                <?= $crime['USER_NAME'] ?><br>
                <?= $crime['USER_EMAIL'] ?><br>
            </td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="crm_id" value="<?= $crime['CRM_ID'] ?>">
                    <select name="crm_status">
                        <option value="Pending" <?= $crime['CRM_STATUS'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Resolved" <?= $crime['CRM_STATUS'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                    <button type="submit" name="update_status">Update</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="crm_id" value="<?= $crime['CRM_ID'] ?>">
                    <button type="submit" name="delete_crime">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <footer>
        Developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narendra Pal sir.
    </footer>
</body>
</html>
