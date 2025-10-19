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
        <a class="nav-link active" aria-current="page" href="/index.php">Home</a>

        <?php if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])): ?>
          <span class="nav-link">Hi, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
          <a class="nav-link btn btn-outline-danger ms-2" href="/logout.php">Logout</a>
        <?php else: ?>
          <a class="nav-link" href="/login.php">Admin Login</a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>
<div class="container my-5">
        <a class='btn btn-primary btn-sm' href="/create.php" role="button">New Event</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Time</th>
                    <th>Image_preview</th>
                    <th>Image_url</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
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

                    $sql = "SELECT * FROM events";
                    $result = mysqli_query($connect, $sql);

                    if(!$result){
                        die("invalid query: " . $connection->error);
                    }

                    while($row = $result->fetch_assoc()){
                        echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[title]</td>
                            <td>$row[description]</td>
                            <td>$row[location]</td>
                            <td>$row[date_time]</td>
                            <td><img src='" . $row['image_url'] . "' alt='" . $row['title'] . "' width='150'></td>
                            <td>$row[image_url]</td>
                            <td>
                                <a class='btn btn-primary btn-sm' href='/edit.php?id=$row[id]'>Edit</a>
                                <a 
                                    href='/delete.php?id={$row['id']}' 
                                    class='btn btn-danger btn-sm'
                                    onclick=\"return confirm('Are you sure you want to delete this event?');\"
                                >
                                    Delete
                                </a>
                            </td>
                        </tr>
                        ";
                    }
                ?>

                
            </tbody>
        </table>
    </div>
</body>
</html>