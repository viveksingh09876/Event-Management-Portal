<?php
session_start();

if (isset($_SESSION['success'])) {
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>" . htmlspecialchars($_SESSION['success']) . "</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
    ";
    unset($_SESSION['success']);
}

$connect = mysqli_connect(
    'db',             // service name (Docker)
    'event_manager',  // username
    'password',       // password
    'event_manager'   // database name
);

if (!$connect) {
    die("Connection failed: " . htmlspecialchars(mysqli_connect_error()));
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Both username and password are required!";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = "Username must be between 3 and 50 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Username can only contain letters, numbers, and underscores.";
    } else {
        $stmt = $connect->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        if (!$stmt) {
            die("Statement preparation failed: " . htmlspecialchars($connect->error));
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = "Invalid username or password!";
        }

        $stmt->close();
    }

    $connect->close();
}

include __DIR__ . '/assets/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container-fluid login-container">
    <div class="row h-100">
      <!-- Left Half - Image -->
      <div class="col-md-6 d-none d-md-block login-image"></div>

      <!-- Right Half - Login Form -->
      <div class="col-md-6 col-12 login-form">
        <div class="form-box">
          <h2 class="mb-4">Login</h2>

          <?php if (!empty($error)): ?>
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              <strong><?= htmlspecialchars($error) ?></strong>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='close'></button>
            </div>
          <?php endif; ?>

          <form action="login.php" method="POST" novalidate>
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input 
                type="text" 
                class="form-control" 
                id="username" 
                name="username" 
                pattern="[A-Za-z0-9_]+" 
                minlength="3" 
                maxlength="50" 
                required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input 
                type="password" 
                class="form-control" 
                id="password" 
                name="password" 
                minlength="6" 
                required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
