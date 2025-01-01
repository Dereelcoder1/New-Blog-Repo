<?php
$servername = "if0_38012620_blog_system";
$username = "if0_38012620"; // Default username
$password = "2a2SAxYWtCji";     // Default password
$dbname = "sql206.infinityfree.com"; // The database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

