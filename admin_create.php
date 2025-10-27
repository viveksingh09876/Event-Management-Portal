<?php
session_start();

$connect = mysqli_connect(
    'db',             // Docker service name for MySQL
    'event_manager',  // MySQL username
    'password',       // MySQL password
    'event_manager'   // Database name
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$table_name = "admins";
$username = "";
$password = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $errorMessage = "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $errorMessage = "Username must be 3–20 characters long and contain only letters, numbers, or underscores.";
    } elseif (strlen($password) < 8) {
        $errorMessage = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errorMessage = "Password must include both letters and numbers.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO $table_name (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($connect, $sql);

        if (!$stmt) {
            $errorMessage = "Statement preparation failed: " . htmlspecialchars(mysqli_error($connect));
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed);

            if (!mysqli_stmt_execute($stmt)) {
                if (mysqli_errno($connect) == 1062) { // Duplicate username
                    $errorMessage = "Username already exists. Please choose another.";
                } else {
                    $errorMessage = "Database error: " . htmlspecialchars(mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            } else {
                mysqli_stmt_close($stmt);
                $_SESSION['success'] = "Admin added successfully!";
                header("Location: /login.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container my-5">
    <h2>Add Admin</h2>

    <!-- Error Message -->
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><?php echo htmlspecialchars($errorMessage); ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Success Message -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo htmlspecialchars($successMessage); ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-6">
                <input 
                    type="text" 
                    class="form-control" 
                    name="username" 
                    value="<?php echo htmlspecialchars($username); ?>" 
                    required
                    minlength="3"
                    maxlength="20"
                    pattern="[A-Za-z0-9_]+"
                    title="Only letters, numbers, and underscores allowed (3–20 chars)"
                >
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-6">
                <input 
                    type="password" 
                    class="form-control" 
                    name="password" 
                    required
                    minlength="8"
                    title="Password must contain at least 8 characters with letters and numbers"
                >
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col-sm-3 d-grid">
                <a class="btn btn-outline-secondary" href="/admin-dashboard.php" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>
</body>
</html>
