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

// Display statistics
echo "<p>Total Reported Crimes: $totalCrimes</p>";
echo "<p>Resolved Crimes: $resolvedCrimes</p>";
echo "<p>Pending Crimes: $pendingCrimes</p>";

$conn->close();
?>
