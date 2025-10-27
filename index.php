<?php
include __DIR__ . '/assets/navbar.php';

$connect = mysqli_connect(
    'db',             // Docker service name
    'event_manager',  // MySQL username
    'password',       // MySQL password
    'event_manager'   // Database name
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM events ORDER BY date_time ASC";
$stmt = mysqli_prepare($connect, $sql);

if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars(mysqli_error($connect)));
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "<div class='container my-5 text-center'><h4>No events available at the moment.</h4></div>";
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book My Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
<div class="container my-4">
  <div class="row">
    <?php while ($event = mysqli_fetch_assoc($result)) { ?>
      <div class="col-md-4 my-3">
        <div class="card mx-auto shadow-sm" style="width: 18rem;">
          <img src="<?= htmlspecialchars($event['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($event['title']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">📍 <?= htmlspecialchars($event['location']) ?></li>
            <li class="list-group-item">🕒 <?= date("d M Y, h:i A", strtotime($event['date_time'])) ?></li>
          </ul>
          <div class="card-body text-center">
            <a class="btn btn-primary" href="/registration.php?event_id=<?= urlencode($event['id']) ?>" role="button">
              Register for this event
            </a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<?php
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
</body>
</html>
