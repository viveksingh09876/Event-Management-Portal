<?php
session_start();

$connect = mysqli_connect(
    'db',             // Docker service name
    'event_manager',  // MySQL username
    'password',       // MySQL password
    'event_manager'   // Database name
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = "";
$title = "";
$description = "";
$location = "";
$date_time = "";
$image_url = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and fetch event by ID
    if (!isset($_GET["id"]) || !filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
        header("location: /admin_dashboard.php");
        exit;
    }

    $id = (int) $_GET["id"];

    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);

    if (!$stmt) {
        die("Prepare failed: " . htmlspecialchars(mysqli_error($connect)));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        header("location: /admin_dashboard.php");
        exit;
    }

    $title = htmlspecialchars($row["title"]);
    $description = htmlspecialchars($row["description"]);
    $location = htmlspecialchars($row["location"]);
    $date_time = htmlspecialchars($row["date_time"]);
    $image_url = htmlspecialchars($row["image_url"]);

    mysqli_stmt_close($stmt);
} else {
    $id = $_POST["id"];
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $location = trim($_POST["location"]);
    $date_time = trim($_POST["date_time"]);
    $image_url = trim($_POST["image_url"]);

    if (
        !filter_var($id, FILTER_VALIDATE_INT) ||
        empty($title) ||
        empty($description) ||
        empty($location) ||
        empty($date_time) ||
        empty($image_url)
    ) {
        $errorMessage = "All fields are required and must be valid.";
    } else {
        $sql = "UPDATE events 
                SET title = ?, description = ?, location = ?, date_time = ?, image_url = ?
                WHERE id = ?";
        $stmt = mysqli_prepare($connect, $sql);

        if (!$stmt) {
            die("Prepare failed: " . htmlspecialchars(mysqli_error($connect)));
        }

        mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $location, $date_time, $image_url, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Event updated successfully!";
            mysqli_stmt_close($stmt);
            mysqli_close($connect);
            header("location: /admin_dashboard.php");
            exit;
        } else {
            $errorMessage = "Error updating record: " . htmlspecialchars(mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Edit Event</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?= $errorMessage ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($title) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="description" value="<?= htmlspecialchars($description) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Location</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($location) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Date and Time</label>
                <div class="col-sm-6">
                    <input type="datetime-local" class="form-control" name="date_time" value="<?= htmlspecialchars($date_time) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Image URL</label>
                <div class="col-sm-6">
                    <input type="url" class="form-control" name="image_url" value="<?= htmlspecialchars($image_url) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/admin_dashboard.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
