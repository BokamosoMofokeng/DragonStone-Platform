<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';

if (empty($_GET['id'])) {
    header('Location: community.php');
    exit();
}

$id = (int)$_GET['id'];

$pst = $conn->prepare('SELECT p.*, u.name FROM posts p LEFT JOIN users u ON p.user_id = u.id WHERE p.id = ?');
$pst->bind_param('i', $id);
$pst->execute();
$post = $pst->get_result()->fetch_assoc();

if (!$post || !$post['approved']) {
    echo '<div class="container py-5"><p>Post not found.</p></div>';
    include 'includes/footer.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
    $uid = $_SESSION['user_id'] ?? null;
    $stmt = $conn->prepare('INSERT INTO comments (post_id, user_id, body, approved, created_at) VALUES (?, ?, ?, ?, NOW())');
    $ap = 0;
    $stmt->bind_param('iisi', $id, $uid, $_POST['comment'], $ap);
    $stmt->execute();

    $_SESSION['flash'] = 'Comment submitted for review.';
    header('Location: view_post.php?id=' . $id);
    exit();
}
?>

<div class="container py-5">
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
    <p class="text-muted">By <?php echo htmlspecialchars($post['name']); ?> at <?php echo $post['created_at']; ?></p>

    <h4>Comments</h4>
    <?php
    $c = $conn->prepare('SELECT c.*, u.name FROM comments c LEFT JOIN users u ON c.user_id = u.id WHERE c.post_id = ? AND c.approved = 1');
    $c->bind_param('i', $id);
    $c->execute();
    $cr = $c->get_result();

    while ($com = $cr->fetch_assoc()) {
        echo '<div class="border p-2 mb-2">';
        echo '<p>' . nl2br(htmlspecialchars($com['body'])) . '</p>';
        echo '<p class="text-muted">By ' . htmlspecialchars($com['name']) . ' at ' . $com['created_at'] . '</p>';
        echo '</div>';
    }

    if (!empty($_SESSION['user_id'])):
    ?>
        <form method="post">
            <textarea name="comment" class="form-control mb-2" required></textarea>
            <button class="btn btn-primary">Submit Comment</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Login</a> to comment.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
