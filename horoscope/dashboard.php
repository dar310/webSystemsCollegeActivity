<?php
session_start();
if (!isset($_SESSION['birth_month']) && !isset($_SESSION['birth_day'])) {
    header('Location: login.php');
    exit();
}
$birth_month=$_SESSION['birth_month'];
$birth_day=$_SESSION['birth_day'];
include_once("config.php");

$result = mysqli_query($conn, "SELECT * FROM zodiac WHERE ($birth_month > month_min OR ($birth_month = month_min AND $birth_day >= day_min)) AND ($birth_month < month_max OR ($birth_month = month_max AND $birth_day <= day_max))");

while($user_data = mysqli_fetch_array($result))
{
    $sign_name = $user_data['sign_name'];
    $description = $user_data['description'];
    $daily_horoscope = $user_data['daily_horoscope'];
    $min_month = $user_data['month_min'];
    $min_day = $user_data['day_min'];
    $max_month = $user_data['month_max'];
    $max_day = $user_data['day_max'];
    $image_path = $user_data['image_path'];
}
// echo "$image_path";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <!-- change the placeholder.css -->
        <link rel="stylesheet" href="styles/placeholder.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="mb3">
        <div class="card" style="width: 18rem;">
        <img src=<?php echo "$image_path";?> class="card-img-top" alt="No Image">
        <div class="card-body">
            <h5 class="card-title"><?php echo "$sign_name"?></h5>
            <p class="card-text"><?php echo "$daily_horoscope"?>.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
        </div>
        </div>
        <a class="btn btn-primary" href="logout.php" role="button">Logout</a>
    </body>
</html>
