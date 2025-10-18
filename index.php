<?php
include __DIR__ . '/assets/navbar.php';

$connect = mysqli_connect(
'db', # service name
'event_manager', # username
'password', # password
'event_manager' # db table
);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$table_name = "events";
$query = "SELECT * FROM events ORDER BY date_time ASC";
$result = mysqli_query($connect, $query);
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
<div class="container">
  <div class="row">
    <?php while ($event = mysqli_fetch_assoc($result)) { ?>
      <div class="col-md-4 my-3">
        <div class="card mx-auto" style="width: 18rem;">
          <img src="<?php echo $event['image_url']; ?>" class="card-img-top" alt="<?php echo $event['title']; ?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo $event['title']; ?></h5>
            <p class="card-text"><?php echo $event['description']; ?></p>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">📍 <?php echo $event['location']; ?></li>
            <li class="list-group-item">🕒 <?php echo date("d M Y, h:i A", strtotime($event['date_time'])); ?></li>
          </ul>
          <div class="card-body">
            <a href="#" class="card-link">Register for the event</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>


    
</body>
</html>