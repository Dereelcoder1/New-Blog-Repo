<?php
// Include the database connection file
include '../admin/backend/db_connection.php'; // Adjust the path if necessary

// Initialize variables
$posts = [];
$comments = [];
$likes = 0;
$dislikes = 0;

// Check if the 'id' is present in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id']; // Ensure it's an integer to avoid SQL injection

    // Fetch the blog post using a prepared statement
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $posts = $result->fetch_all(MYSQLI_ASSOC); // Fetch the post as an array
        $blogTitle = htmlspecialchars($posts[0]['title']); // Get the blog title

        // Fetch comments for this blog post
        $commentStmt = $conn->prepare("SELECT * FROM comments WHERE blog_id = ?");
        $commentStmt->bind_param("i", $id);
        $commentStmt->execute();
        $commentResult = $commentStmt->get_result();

        if ($commentResult && $commentResult->num_rows > 0) {
            $comments = $commentResult->fetch_all(MYSQLI_ASSOC); // Fetch all comments
        }
        $commentStmt->close();

        // Fetch like and dislike counts
        $likesQuery = "SELECT COUNT(*) FROM reactions WHERE blog_id = ? AND reaction = 'like'";
        $dislikesQuery = "SELECT COUNT(*) FROM reactions WHERE blog_id = ? AND reaction = 'dislike'";

        $likesStmt = $conn->prepare($likesQuery);
        $likesStmt->bind_param("i", $id);
        $likesStmt->execute();
        $likesStmt->bind_result($likes);
        $likesStmt->fetch();
        $likesStmt->close();

        $dislikesStmt = $conn->prepare($dislikesQuery);
        $dislikesStmt->bind_param("i", $id);
        $dislikesStmt->execute();
        $dislikesStmt->bind_result($dislikes);
        $dislikesStmt->fetch();
        $dislikesStmt->close();

    } else {
        echo "No blog post found.";
        exit;
    }

    $stmt->close();
} else {
    echo "Invalid or missing ID in the URL.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $blogTitle; ?></title>
    <link rel="stylesheet" href="readmore.css">
</head>
<body>
    <div class="blog-container">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="blog-post">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="meta">
                        Posted by <?php echo htmlspecialchars($post['posted_by']); ?> on <?php echo date("F j, Y", strtotime($post['created_at'])); ?>
                    </p>
                    
                    <div class="blog-content">
                        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    </div>
                </div>

                <div class="like-dislike-buttons">
                    <button class="like-button">Like (<?php echo $likes; ?>)</button>
                    <button class="dislike-button">Dislike (<?php echo $dislikes; ?>)</button>
                </div>

                <div class="comments-section">
                    <h3>Comments (<?php echo count($comments); ?>)</h3>
                    <?php if (empty($comments)): ?>
                        <p>Be the first to comment!</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                                <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <form action="../admin/backend/add_comment.php" method="POST">
                        <textarea name="comment" class="comment-box" placeholder="Write a comment..." required></textarea>
                        <input type="hidden" name="blog_id" value="<?php echo $id; ?>">
                        <button type="submit" class="comment-btn">Post Comment</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No blog posts found.</p>
        <?php endif; ?>
        <a href="index.php" class="back-btn">Back to Blog</a>
    </div>


    <script>
        document.querySelectorAll(".like-button, .dislike-button").forEach(button => {
    button.addEventListener("click", function () {
        const blogId = <?php echo $id; ?>;
        const reaction = this.classList.contains("like-button") ? "like" : "dislike";

        fetch("../admin/backend/update_reaction.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `blog_id=${blogId}&reaction=${reaction}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update counts
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
});

    </script>
</body>
</html>
