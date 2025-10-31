<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $uid = $_SESSION['user_id'];

    $stmt = $conn->prepare('INSERT INTO posts (user_id, title, body, approved, created_at) VALUES (?, ?, ?, ?, NOW())');
    $approved = 0;
    $stmt->bind_param('issis', $uid, $title, $body, $approved);

    if ($stmt->execute()) {
        $_SESSION['flash'] = 'Post submitted for review.';
        header('Location: community.php');
        exit();
    } else {
        $error = $stmt->error;
    }
}
?>

<div class="container py-5">
    <h2>Create Post</h2>
    <?php if (!empty($error)) echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>'; ?>
    <form method="post" class="col-md-8">
        <input class="form-control mb-3" name="title" placeholder="Title" required>
        <textarea class="form-control mb-3" name="body" rows="6" placeholder="Share your project or tip" required></textarea>
        <button class="btn btn-success">Submit</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
