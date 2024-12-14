

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Page</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
    <script>
        function initMap() {
            var defaultLocation = { lat: 37.7749, lng: -122.4194 }; // Default coordinates (San Francisco)
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: defaultLocation
            });

            var marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                document.getElementById('coordinates').value = event.latLng.lat() + ',' + event.latLng.lng();
            });
        }
    </script>
</head>
<body onload="initMap()">
    <h1>Emergency Page</h1>
    <form method="POST" action="">
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br><br>

        <label for="state">State:</label>
        <input type="text" id="state" name="state" required><br><br>

        <label for="coordinates">Coordinates:</label>
        <input type="text" id="coordinates" name="coordinates" readonly required><br><br>

        <div id="map" style="width: 100%; height: 300px; border: 1px solid black;"></div><br>

        <button type="submit">Submit</button>
    </form>

    <?php
// Include the required libraries and establish database connection
include "connection.php";
require "./PHPMailer-master/src/PHPMailer.php";
require "./PHPMailer-master/src/SMTP.php";
require "./PHPMailer-master/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = $_POST['city'];
    $state = $_POST['state'];
    $coordinates = $_POST['coordinates'];

    // Insert into database
    $sql = "INSERT INTO emergency (city, state, coordinates) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $city, $state, $coordinates);

    if ($stmt->execute()) {
        echo "<p>Record added successfully!</p>";

        // Send email
        $email = "recipient@example.com"; // Replace with actual recipient email

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "yourEmailID@gmail.com"; // Replace with your email
        $mail->Password = "YourPassword"; // Replace with your email password
        $mail->setFrom("yourEmailID@gmail.com", "Emergency Alert");
        $mail->addAddress($email);
        $mail->Subject = "New Emergency Report";
        $mail->Body = "An emergency has been reported:\nCity: $city\nState: $state\nCoordinates: $coordinates.";

        try {
            $mail->send();
            echo "<p>Email sent successfully!</p>";
        } catch (Exception $e) {
            echo "<p>Failed to send email: " . $mail->ErrorInfo . "</p>";
        }
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}
?>

</body>
</html>
