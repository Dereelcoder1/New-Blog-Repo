<?php
include '../admin/backend/db_connection.php';
if(isset($_GET['id'])){
    // Query to fetch blog posts
    $id = $_GET['id'];
    $query = "SELECT * FROM `blogs` WHERE id={$id} ORDER BY created_at DESC";
    $result = $conn->query($query);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    // print_r($result->fetch_all(MYSQLI_ASSOC));

}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read More</title>
</head>
<body>
    <div id="content">
        <div class="title"><?=  blogs['title'] ?></div>



        ?>
    </div>


</body>
</html>