<?php
include 'db_connection.php'; // Include DB connection
// header('Content-Type: application/json');
// print_r($_GET);
if(isset($_GET['blogs'])){
    // Query to fetch blog posts
    $query = "SELECT * FROM `blogs` ORDER BY created_at DESC";
    $result = $conn->query($query);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    // print_r($result->fetch_all(MYSQLI_ASSOC));
    $blogs = [];;
    
    // Fetch data and add to the array
    foreach ($rows as $key) {
        array_push($blogs,$key);
    }
    
    // print_r($blogs);
    
    // Return JSON response
    echo json_encode($blogs);
}

$conn->close();

