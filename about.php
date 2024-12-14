<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Crime Management System</title>
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

        .team, .mentor, .contact {
            margin: 50px 20px;
            text-align: center;
        }

        .team img, .mentor img {
            width: 150px;
            border-radius: 50%;
        }

        #photo {
            width: 150px;
            border-radius: 50%;
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

        p{
            font-weight: 800;
        }

        h2{
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

    <div class="hero">
        <h1>About Crime Management System</h1>
        <p>The Crime Management System is a platform where users can report crimes and provide detailed information. This platform aims to help authorities manage crime data effectively and take quick action. Here are some key features:</p>
    </div>

    <div class="features">
        <h2>Features of the System</h2>
        <p>- Report crimes with detailed descriptions, locations, and categories.</p>
        <p>- Admin dashboard with a heatmap to visualize crime density across India.</p>
        <p>- Automatic email notifications sent to higher authorities whenever a crime is reported.</p>
        <p>- Crime reports can be filtered and sorted based on various criteria for easy management.</p>
        <p>- AI chatbot integrated to assist users with their queries.</p>
        <p>- Emergency button for women, which sends a message with location details to authorities and guardians when in danger.</p>
    </div>

    <div class="team">
        <h2>Meet the Team</h2>
        <div>
            <img src="s.png" alt="Suryansh Pratap Singh">
            <p>Suryansh Pratap Singh</p>
        </div>
        <div id="photo">
            <img src="t.png" alt="Tanisha Agarwal">
            <p>Tanisha Agarwal</p>
        </div>
    </div>

    <div class="mentor">
        <h2>Our Mentor</h2>
        <div>
            <img src="n.png" alt="Narendra Pal Singh Rathore">
            <p>Narendra Pal Singh Rathore</p>
        </div>
    </div>

    <div class="contact">
        <h2>Contact</h2>
        <p>Email: <a href="mailto:suryansh.singh22@0573@acropolis.in">suryansh.singh22@0573@acropolis.in</a></p>
        <p>Phone: 7869999794</p>
    </div>

    <footer>
        This project is developed by Suryansh Pratap Singh and Tanisha Agrawal under the guidance of Narendra Pal Singh Rathore.
    </footer>

</body>
</html>
