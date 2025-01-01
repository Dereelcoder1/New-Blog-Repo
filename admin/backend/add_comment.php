<?php
include '../admin/backend/db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['blog_id'], $_POST['comment'])) {
        $blog_id = (int)$_POST['blog_id'];
        $comment = $_POST['comment'];
        $username = "Anonymous"; // Set default username (replace with user session data if available)

        $stmt = $conn->prepare("INSERT INTO comments (blog_id, username, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $blog_id, $username, $comment);

        if ($stmt->execute()) {
            header("Location: blog_page.php?id=$blog_id"); // Redirect to the blog page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid request";
    }
} else {
    echo "Invalid method";
}
?>
