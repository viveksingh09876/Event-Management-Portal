<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        die("Invalid event ID.");
    }

    $connect = mysqli_connect(
        'db',             // Docker service name
        'event_manager',  // MySQL username
        'password',       // MySQL password
        'event_manager'   // Database name
    );

    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);

    if (!$stmt) {
        die("Statement preparation failed: " . htmlspecialchars(mysqli_error($connect)));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing statement: " . htmlspecialchars(mysqli_stmt_error($stmt)));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}

header("Location: /admin_dashboard.php");
exit;
?>
