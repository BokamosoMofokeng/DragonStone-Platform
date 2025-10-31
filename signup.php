<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['flash'] = "Email already registered. Please log in.";
        header("Location: login.php");
        exit();
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['flash'] = "Signup successful! You can now log in.";
        header("Location: login.php");
    } else {
        $_SESSION['flash'] = "Error creating account: " . $stmt->error;
        header("Location: signup.php");
    }
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<div class="container py-5">
  <h2>Create Account</h2>
  <form method="POST" action="">
      <div class="mb-3">
          <label>Name</label>
          <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Sign Up</button>
      <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
