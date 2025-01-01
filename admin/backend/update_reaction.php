<?php
include '../admin/backend/db_connection.php'; // Database connection

$response = ["success" => false, "error" => "Invalid request"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['blog_id'], $_POST['reaction'])) {
        $blog_id = (int)$_POST['blog_id'];
        $reaction = $_POST['reaction'];

        // Validate reaction type
        if (in_array($reaction, ['like', 'dislike'])) {
            // Optional: Validate if the blog exists
            $blogExistsQuery = "SELECT id FROM blogs WHERE id = ?";
            $stmt = $conn->prepare($blogExistsQuery);
            $stmt->bind_param("i", $blog_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Insert reaction (optionally with a unique user IP)
                $user_ip = $_SERVER['REMOTE_ADDR']; // Get user IP
                $insertQuery = "INSERT INTO reactions (blog_id, user_ip, reaction) 
                                VALUES (?, ?, ?)
                                ON DUPLICATE KEY UPDATE reaction = ?";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("isss", $blog_id, $user_ip, $reaction, $reaction);

                if ($insertStmt->execute()) {
                    $response = ["success" => true];
                } else {
                    $response = ["success" => false, "error" => $insertStmt->error];
                }
                $insertStmt->close();
            } else {
                $response = ["success" => false, "error" => "Blog post not found"];
            }
            $stmt->close();
        } else {
            $response = ["success" => false, "error" => "Invalid reaction type"];
        }
    }
} else {
    $response = ["success" => false, "error" => "Invalid request method"];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
8uhh7  