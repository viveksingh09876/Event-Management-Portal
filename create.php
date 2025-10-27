<?php
session_start();

$connect = mysqli_connect(
    'db',            // Docker MySQL service name
    'event_manager', // MySQL username
    'password',      // MySQL password
    'event_manager'  // Database name
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$table_name = "events";

$title = $description = $location = $date_time = $image_url = "";
$errorMessage = $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $location = trim($_POST["location"]);
    $date_time = trim($_POST["date_time"]);
    $image_url = trim($_POST["image_url"]);

    if (empty($title) || empty($description) || empty($location) || empty($date_time) || empty($image_url)) {
        $errorMessage = "All fields are required.";
    } elseif (strlen($title) > 255) {
        $errorMessage = "Title cannot exceed 255 characters.";
    } elseif (!preg_match("/^[a-zA-Z0-9 ,.'-]+$/", $location)) {
        $errorMessage = "Location contains invalid characters.";
    } elseif (!filter_var($image_url, FILTER_VALIDATE_URL)) {
        $errorMessage = "Invalid Image URL format.";
    } elseif (strtotime($date_time) === false) {
        $errorMessage = "Please enter a valid date and time.";
    } else {
        $stmt = $connect->prepare("INSERT INTO $table_name (title, description, location, date_time, image_url) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sssss", $title, $description, $location, $date_time, $image_url);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Event created successfully!";
                header("Location: /admin_dashboard.php");
                exit;
            } else {
                $errorMessage = "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errorMessage = "Failed to prepare statement: " . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Create New Event</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><?php echo $errorMessage; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo $_SESSION['success']; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="post">
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Title</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($title); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-6">
                <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Location</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($location); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Date & Time</label>
            <div class="col-sm-6">
                <input type="datetime-local" class="form-control" name="date_time" value="<?php echo htmlspecialchars($date_time); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Image URL</label>
            <div class="col-sm-6">
                <input type="url" class="form-control" name="image_url" value="<?php echo htmlspecialchars($image_url); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col-sm-3 d-grid">
                <a class="btn btn-outline-secondary" href="/admin_dashboard.php" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
