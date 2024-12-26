<?php
include 'db_connection.php';

echo $_SERVER['REQUEST_METHOD'];
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    echo 'correct';
    // Handle image upload
    $image = $_FILES['image_path'];
    $imagePath = 'uploads/' . basename($image['name']);
    move_uploaded_file($image['tmp_name'], $imagePath);

    // Insert blog into database
    $stmt = $conn->prepare("INSERT INTO blogs (title, content, image_path) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $title, $content, $imagePath);
        $stmt->execute();
    
        
        // Redirect or return success response
        header("Location: ../index.html");
        exit();
    }else {
        echo 'cant post blog';
    }
    
}
?>
