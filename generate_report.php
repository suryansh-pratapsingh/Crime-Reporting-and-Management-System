<?php
require_once 'vendor/autoload.php'; // Include the autoload file for DOMPDF

use Dompdf\Dompdf;
use Dompdf\Options;

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crime_reporting_system";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data for the statistics page
$crimeStatusQuery = "SELECT CRM_STATUS, COUNT(*) AS total FROM crime GROUP BY CRM_STATUS";
$crimeCategoryQuery = "SELECT crime_category.CATEGORY_DESC, COUNT(*) AS total 
                       FROM crime 
                       JOIN crime_category ON crime.CRM_CATEGORY_ID = crime_category.CRM_CATEGORY_ID
                       GROUP BY crime_category.CATEGORY_DESC";
$totalCrimesLast24HoursQuery = "SELECT COUNT(*) AS total FROM crime WHERE DATE_TIME > NOW() - INTERVAL 1 DAY";
$crimeByCityQuery = "SELECT CRM_CITY, COUNT(*) AS total FROM crime GROUP BY CRM_CITY ORDER BY total DESC";
$topStatesHighestCrimesQuery = "SELECT CRM_STATE, COUNT(*) AS total 
                                FROM crime 
                                GROUP BY CRM_STATE 
                                ORDER BY total DESC 
                                LIMIT 5";
$topStatesLowestCrimesQuery = "SELECT CRM_STATE, COUNT(*) AS total 
                               FROM crime 
                               GROUP BY CRM_STATE 
                               ORDER BY total ASC 
                               LIMIT 5";
$topStatesResolvedCrimesQuery = "SELECT CRM_STATE, COUNT(*) AS total 
                                  FROM crime 
                                  WHERE CRM_STATUS = 'Resolved' 
                                  GROUP BY CRM_STATE 
                                  ORDER BY total DESC 
                                  LIMIT 5";
$topStatesPendingCrimesQuery = "SELECT CRM_STATE, COUNT(*) AS total 
                                 FROM crime 
                                 WHERE CRM_STATUS = 'Pending' 
                                 GROUP BY CRM_STATE 
                                 ORDER BY total ASC 
                                 LIMIT 5";

$crimeStatusResult = mysqli_query($conn, $crimeStatusQuery);
$crimeCategoryResult = mysqli_query($conn, $crimeCategoryQuery);
$totalCrimesLast24HoursResult = mysqli_query($conn, $totalCrimesLast24HoursQuery);
$crimeByCityResult = mysqli_query($conn, $crimeByCityQuery);
$topStatesHighestCrimesResult = mysqli_query($conn, $topStatesHighestCrimesQuery);
$topStatesLowestCrimesResult = mysqli_query($conn, $topStatesLowestCrimesQuery);
$topStatesResolvedCrimesResult = mysqli_query($conn, $topStatesResolvedCrimesQuery);
$topStatesPendingCrimesResult = mysqli_query($conn, $topStatesPendingCrimesQuery);

// Fetch the data into arrays
$crimeStatusData = [];
while ($row = mysqli_fetch_assoc($crimeStatusResult)) {
    $crimeStatusData[] = $row;
}

$crimeCategoryData = [];
while ($row = mysqli_fetch_assoc($crimeCategoryResult)) {
    $crimeCategoryData[] = $row;
}

$totalCrimesLast24HoursData = mysqli_fetch_assoc($totalCrimesLast24HoursResult);

$crimeByCityData = [];
while ($row = mysqli_fetch_assoc($crimeByCityResult)) {
    $crimeByCityData[] = $row;
}

$topStatesHighestCrimesData = [];
while ($row = mysqli_fetch_assoc($topStatesHighestCrimesResult)) {
    $topStatesHighestCrimesData[] = $row;
}

$topStatesLowestCrimesData = [];
while ($row = mysqli_fetch_assoc($topStatesLowestCrimesResult)) {
    $topStatesLowestCrimesData[] = $row;
}

$topStatesResolvedCrimesData = [];
while ($row = mysqli_fetch_assoc($topStatesResolvedCrimesResult)) {
    $topStatesResolvedCrimesData[] = $row;
}

$topStatesPendingCrimesData = [];
while ($row = mysqli_fetch_assoc($topStatesPendingCrimesResult)) {
    $topStatesPendingCrimesData[] = $row;
}

// Close the database connection
mysqli_close($conn);

if (isset($_POST['generate_pdf'])) {
    // Generate the PDF content
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Crime Statistics Report</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            h1, h2 {
                text-align: center;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Crime Statistics Report</h1>

        <h2>Total Crimes in Last 24 Hours</h2>
        <p><?php echo $totalCrimesLast24HoursData['total']; ?> crimes reported</p>

        <h2>Crime Status Distribution</h2>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crimeStatusData as $status) { ?>
                    <tr>
                        <td><?php echo $status['CRM_STATUS']; ?></td>
                        <td><?php echo $status['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Crime Categories</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crimeCategoryData as $category) { ?>
                    <tr>
                        <td><?php echo $category['CATEGORY_DESC']; ?></td>
                        <td><?php echo $category['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Crimes by City</h2>
        <table>
            <thead>
                <tr>
                    <th>City</th>
                    <th>Total Crimes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crimeByCityData as $city) { ?>
                    <tr>
                        <td><?php echo $city['CRM_CITY']; ?></td>
                        <td><?php echo $city['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Top 5 States with Highest Number of Crimes</h2>
        <table>
            <thead>
                <tr>
                    <th>State</th>
                    <th>Total Crimes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topStatesHighestCrimesData as $state) { ?>
                    <tr>
                        <td><?php echo $state['CRM_STATE']; ?></td>
                        <td><?php echo $state['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Top 5 States with Lowest Number of Crimes</h2>
        <table>
            <thead>
                <tr>
                    <th>State</th>
                    <th>Total Crimes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topStatesLowestCrimesData as $state) { ?>
                    <tr>
                        <td><?php echo $state['CRM_STATE']; ?></td>
                        <td><?php echo $state['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Top 5 States with Highest Number of Resolved Crimes</h2>
        <table>
            <thead>
                <tr>
                    <th>State</th>
                    <th>Total Resolved Crimes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topStatesResolvedCrimesData as $state) { ?>
                    <tr>
                        <td><?php echo $state['CRM_STATE']; ?></td>
                        <td><?php echo $state['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Top 5 States with Lowest Number of Pending Crimes</h2>
        <table>
            <thead>
                <tr>
                    <th>State</th>
                    <th>Total Pending Crimes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topStatesPendingCrimesData as $state) { ?>
                    <tr>
                        <td><?php echo $state['CRM_STATE']; ?></td>
                        <td><?php echo $state['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    // Generate PDF with DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("crime_statistics_report.pdf", ["Attachment" => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
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

        .content {
            text-align: center;
            margin: 50px auto;
            max-width: 600px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .content h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .content form button {
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            background-color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .content form button:hover {
            background-color: #0a0909;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            margin-top: 230px;
            position: fixed; bottom: 0; width: 100%;
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

    <div class="content">
        <h1>Crime Statistics Report Generated</h1>
        <p>Click the button below to download the Crime Statistics Report in PDF format.</p>
        <form method="post">
            <button type="submit" name="generate_pdf">Download Report as PDF</button>
        </form>
    </div>

    <footer>
        Developed by Suryansh Pratap Singh under mentorship of Shushma Khatri
    </footer>

</body>
</html>
