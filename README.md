# ArtOnline
ArtStudio Project - Running with XAMPP

Prerequisites

1. XAMPP installed on your system

2. PHP, MySQL, and Apache enabled in XAMPP

3. Web browser (Chrome, Firefox, Edge, etc.)


Installation and Setup

Step 1: Start XAMPP

Open XAMPP Control Panel.

Start Apache and MySQL services.

Step 2: Place Project in htdocs folder

Navigate to the XAMPP installation directory (e.g., C:\xampp\htdocs\).

Copy your PHP project folder into the htdocs directory.

Step 3: Configure the Database

open xampp Control panel and start apache and mysql

Open browser and enter localhost in URL

Click phpMyAdmin.

Click on Databases and create a new database (e.g., online_art_studio).

Import the database schema:

Click Import.

Choose the SQL file (e.g., online_art_studio.sql) from project DB folder.

Click Go.

Step 4: Configure Database Connection

Edit the database configuration file (e.g., config.php):

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'online_art_studio';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

Step 5: Run the Project

Open a web browser.

Enter the following URL:

http://localhost/artonline/

Your project should now be accessible.
