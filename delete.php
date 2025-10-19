<?php
if(isset($_GET["id"])){
    $id = $_GET["id"];

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

    $sql = "DELETE FROM events WHERE id=$id";
    mysqli_query($connect, $sql);
}
header("location: /admin_dashboard.php");
exit;
?>