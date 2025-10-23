<?php
include __DIR__ . '/assets/navbar.php';

$connect = mysqli_connect(
    'db', 
    'event_manager',
    'password',
    'event_manager'
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$event_name = "";

if ($event_id > 0) {
    $query = "SELECT title FROM events WHERE id = $event_id";
    $result = mysqli_query($connect, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $event_name = $row['title'];
    }
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $event_id = intval($_POST['event_id']);

    if (!empty($username) && !empty($email) && $event_id > 0) {
        $sql = "INSERT INTO users (username, email, event_id)
                VALUES ('$username', '$email', $event_id)";

        if (mysqli_query($connect, $sql)) {
            $success_message = "Registration successful for event: $event_name";
        } else {
            $error_message = "Error: " . mysqli_error($connect);
        }
    } else {
        $error_message = "Please fill all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card mx-auto shadow-lg" style="max-width: 600px; border-radius: 15px;">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-4">Register for Event</h3>

            <?php if ($success_message): ?>
                <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
            <?php elseif ($error_message): ?>
                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">

                <div class="mb-3">
                    <label class="form-label">Event Name</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($event_name); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="/index.php" class="text-decoration-none">← Back to Events</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
