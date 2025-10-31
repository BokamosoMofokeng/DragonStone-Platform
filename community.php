<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';
?>

<div class="container py-5">
    <h2>Community Hub</h2>

    <?php if (!empty($_SESSION['user_id'])): ?>
        <a class="btn btn-primary mb-3" href="post.php">Create Post</a>
    <?php else: ?>
        <p><a href="login.php">Login</a> to join the community.</p>
    <?php endif; ?>

    <?php
    $res = $conn->query('
        SELECT p.id, p.title, p.body, p.created_at, u.name, p.approved
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.id
        WHERE p.approved = 1
        ORDER BY p.created_at DESC
    ');

    while ($r = $res->fetch_assoc()) {
    ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5><?php echo htmlspecialchars($r['title']); ?></h5>
                <p><?php echo nl2br(htmlspecialchars($r['body'])); ?></p>
                <p class="text-muted">
                    By <?php echo htmlspecialchars($r['name']); ?> at <?php echo $r['created_at']; ?>
                </p>
                <a href="view_post.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-secondary">
                    View
                </a>
            </div>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
