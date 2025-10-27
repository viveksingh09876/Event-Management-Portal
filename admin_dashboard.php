<?php
session_start();

if (isset($_SESSION['success'])) {
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>{$_SESSION['success']}</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
    ";
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Book My Events</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Actions</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#UpdateEvents">Update Events</a></li>
            <li><a class="dropdown-item" href="#RegistrationDetails">Event Registration Details</a></li>
          </ul>
        </li>
        <a class="nav-link active" aria-current="page" href="/index.php">Home</a>

        <?php if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])): ?>
          <span class="nav-link">Hi, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
          <a class="nav-link btn btn-outline-danger ms-2" href="/logout.php">Logout</a>
        <?php else: ?>
          <a class="nav-link" href="/login.php">Admin Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<div class="text-end my-3 mx-3">
  <a class='btn btn-primary btn-sm' href="/admin_create.php" role="button">Add Admin</a>
</div>

<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white text-center">
      <h4 class="mb-0" id="UpdateEvents">Update Events</h4>
    </div>

    <div class="text-end my-3 mx-3">
      <a class='btn btn-primary btn-sm' href="/create.php" role="button">New Event</a>
    </div>

    <br>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Location</th>
          <th>Time</th>
          <th>Image Preview</th>
          <th>Image URL</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $connect = mysqli_connect(
            'db',           // service name
            'event_manager',// username
            'password',     // password
            'event_manager' // database name
        );

        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $table_name = "events";

        $sql = "SELECT id, title, description, location, date_time, image_url FROM $table_name";
        $stmt = mysqli_prepare($connect, $sql);

        if (!$stmt) {
            die("Statement preparation failed: " . htmlspecialchars(mysqli_error($connect)));
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            die("Query execution failed: " . htmlspecialchars(mysqli_error($connect)));
        }

        while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . htmlspecialchars($row['description']) . "</td>
                <td>" . htmlspecialchars($row['location']) . "</td>
                <td>" . htmlspecialchars($row['date_time']) . "</td>
                <td><img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['title']) . "' width='150'></td>
                <td>" . htmlspecialchars($row['image_url']) . "</td>
                <td>
                    <a class='btn btn-primary btn-sm' href='/edit.php?id=" . urlencode($row['id']) . "'>Edit</a>
                    <a 
                        href='/delete.php?id=" . urlencode($row['id']) . "' 
                        class='btn btn-danger btn-sm'
                        onclick=\"return confirm('Are you sure you want to delete this event?');\"
                    >
                        Delete
                    </a>
                </td>
            </tr>
            ";
        }

        mysqli_stmt_close($stmt);
        ?>
      </tbody>
    </table>
  </div>

  <div class="container my-5">
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white text-center">
        <h4 class="mb-0" id="RegistrationDetails">Event Registration Details</h4>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>Event Name</th>
            <th>Username</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "
            SELECT 
              e.title AS event_name,
              u.username,
              u.email
            FROM 
              users AS u
            JOIN 
              events AS e
            ON 
              u.event_id = e.id
            ORDER BY 
              e.title;
          ";

          $stmt = mysqli_prepare($connect, $sql);
          if (!$stmt) {
              die("Statement preparation failed: " . htmlspecialchars(mysqli_error($connect)));
          }

          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "
                  <tr>
                      <td>" . htmlspecialchars($row['event_name']) . "</td>
                      <td>" . htmlspecialchars($row['username']) . "</td>
                      <td>" . htmlspecialchars($row['email']) . "</td>
                  </tr>
                  ";
              }
          } else {
              echo "
              <tr>
                  <td colspan='3'>No registrations found</td>
              </tr>
              ";
          }

          mysqli_stmt_close($stmt);
          mysqli_close($connect);
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
