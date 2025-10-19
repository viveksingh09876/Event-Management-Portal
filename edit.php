<?php
session_start();

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

$id = "";
$title = "";
$description = "";
$location = "";
$date_time = "";
$image_url = "";

$errorMessage = "";
$successMessage = "";

if($_SERVER['REQUEST_METHOD']=='GET'){
    //GET method
    if(!isset($_GET["id"])){
        header("location: /admin_dashboard.php");
        exit;
    }

    $id=$_GET["id"];

    $sql = "SELECT * FROM events WHERE id=$id";
    $result = mysqli_query($connect, $sql);
    $row = $result->fetch_assoc();

    if(!$row){
    header("location: /admin_dashboard.php");
    exit;
    }

    $title = $row["title"];
    $description = $row["description"];
    $location = $row["location"];
    $date_time = $row["date_time"];
    $image_url = $row["image_url"];
}else{
    //POST method
    $id = $_POST["id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $location = $_POST["location"];
    $date_time = $_POST["date_time"];
    $image_url = $_POST["image_url"];

    do{
        if(empty($title)||empty($description)||empty($location)||empty($date_time)||empty($image_url)){
                $errorMessage = "All the fields are required";
                break;
            }

        $sql = "UPDATE events " . 
        "SET title = '$title', description = '$description', location = '$location', date_time = '$date_time', image_url = '$image_url' " . 
        "WHERE id = $id";

        $result = mysqli_query($connect, $sql);

        if(!$result){
            $errorMessage = "Invalid query: ". $connection->error;
            break;
        }

        $_SESSION['success'] = "Event updated successfully!";
        header("location: /admin_dashboard.php");
        exit;
    }while (true);
}

                    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Edit Events</h2>
        <?php
        if(!empty($errorMessage)){
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='close'></button>
            </div>
            ";
        }
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
                </div>

            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
                </div>

            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Location</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="location" value="<?php echo $location; ?>">
                </div>

            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Date and Time</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="date_time" value="<?php echo $date_time; ?>">
                </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Image_Url</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="image_url" value="<?php echo $image_url; ?>">
                </div>

            </div>
            <?php
            if(!empty($successMessage)){
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='close'></button>
            </div>
            ";
        }
            ?>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/admin-dashboard.php" role="button">Cancel</a>
                </div>

            </div>

        </form>
    </div>
</body>
</html>