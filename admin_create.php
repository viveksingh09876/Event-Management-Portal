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

$table_name = "admins";

$username = "";
$password = "";

$errorMessage = "";
$successMessage = "";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    do{
        if(empty($username)||empty($password)){
                $errorMessage = "All the fields are required";
                break;
            }

        $sql = "INSERT INTO admins (username,password)".
                    "VALUES ('$username','$hashed')";
                    $result = mysqli_query($connect, $sql);

        if(!$result){
            $errorMessage = "Invalid query: ". htmlspecialchars($connect->error);
            break;
        }

        $username = "";
        $password = "";

        $_SESSION['success'] = "Admin Added successfully!";
        header("location: /login.php");
        exit;
    }while (true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Add Admin</h2>
        <?php
        if(!empty($errorMessage)){
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>htmlspecialchars($errorMessage)</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='close'></button>
            </div>
            ";
        }
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>

            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="password" value="<?php echo $password; ?>" required>
                </div>

            </div>
            <?php
            if(!empty($successMessage)){
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>htmlspecialchars($successMessage)</strong>
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