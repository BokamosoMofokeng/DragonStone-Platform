<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['flash'] = "Welcome back!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['flash'] = "Incorrect password.";
        }
    } else {
        $_SESSION['flash'] = "No account found with that email.";
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="container py-5">
  <h2>Login</h2>
  <form method="POST" action="">
      <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <p class="mt-3">Don’t have an account? <a href="signup.php">Sign up</a>.</p>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
