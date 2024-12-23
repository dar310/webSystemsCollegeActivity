<?php
session_start();
if (!isset($_SESSION['birth_month']) && !isset($_SESSION['birth_day'])) {
    header('Location: login.php');
    exit();
}
$birth_month=$_SESSION['birth_month'];
$birth_day=$_SESSION['birth_day'];
include_once("config.php");

function convertMonthNo($monthNum){
    switch($monthNum) {
        case 1: return "January"; 
        case 2: return "February"; 
        case 3: return "March"; 
        case 4: return "April"; 
        case 5: return "May"; 
        case 6: return "June"; 
        case 7: return "July"; 
        case 8: return "August"; 
        case 9: return "September"; 
        case 10: return "October"; 
        case 11: return "November"; 
        case 12: return "December"; 
        default: return null;
    }
}
//This function is for printing the modal part for each zodiac signs
function printZodiacDetails($zodiacSign) {
    global $conn;

    $query = "SELECT * FROM zodiac WHERE sign_name = '$zodiacSign'";
    $result = mysqli_query($conn, $query);
    $zodiac_data = mysqli_fetch_array($result);
    $sign_name = lcfirst($zodiac_data['sign_name']);
    $min_month= convertMonthNo($zodiac_data['month_min']);
    $max_month= convertMonthNo($zodiac_data['month_max']);
    $min_day=$zodiac_data['day_min'];
    $max_day=$zodiac_data['day_max'];

    if ($zodiac_data) {
        echo '
        <div class="modal fade" id="' . htmlspecialchars($sign_name). '"-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">' . htmlspecialchars($zodiac_data['sign_name']) . '</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="text-center">Start and End Month</h3>
                        <h4 class="text-center">'. htmlspecialchars($min_month).' '.htmlspecialchars($min_day).' - '.htmlspecialchars($max_month).' '.htmlspecialchars($max_day).'<h4><br>
                        <h3>About this Zodiac Sign</h3>
                        <p>' . htmlspecialchars($zodiac_data['description']) . '</p>
                        <h3>Today\'s Horoscope</h3>
                        <p>' . htmlspecialchars($zodiac_data['daily_horoscope']) . '</p>
                    </div>
                </div>
            </div>
        </div>';
    } else {
        // If no data found for the zodiac sign
        echo '<p>No details found for this zodiac sign.</p>';
    }
}
function printZodiacCard($zodiacSign) {
    // Example logic to fetch data for the zodiac sign. Replace with actual data from the database.
    global $conn; // Access the global connection variable

    // Query to fetch zodiac details for the given sign
    $query = "SELECT * FROM zodiac WHERE sign_name = '$zodiacSign'";
    $result = mysqli_query($conn, $query);
    $zodiac_data = mysqli_fetch_array($result);
    $sign_name = $zodiac_data['sign_name'];
    $l_sign_name = lcfirst($zodiac_data['sign_name']);
    $image_path = $zodiac_data['image_path'];

    if ($zodiac_data) {
        echo '
        <div class="col-12 col-sm-6 col-md-3 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">' . htmlspecialchars($sign_name) . '</h5>
                </div>
                <img src="' . htmlspecialchars($image_path) . '" class="card-img-bottom" alt="' . htmlspecialchars($sign_name) . ' Image" alt="zodiac-' . htmlspecialchars($sign_name) . '" data-bs-toggle="modal" data-bs-target="#' . htmlspecialchars($l_sign_name) . '">
            </div>
        </div>';
    } else {
        // If no data found for the zodiac sign
        echo '<p>No details found for this zodiac sign.</p>';
    }
}
$result = mysqli_query($conn, "SELECT * 
FROM zodiac 
WHERE (
    -- Case 1: Range is within the same year
    (month_min < month_max OR 
     (month_min = month_max AND day_min <= day_max)) 
    AND (
        ($birth_month > month_min OR ($birth_month = month_min AND $birth_day >= day_min)) 
        AND 
        ($birth_month < month_max OR ($birth_month = month_max AND $birth_day <= day_max))
    )
) 
OR (
    -- Case 2: Range spans across years (e.g., December to January)
    (month_min > month_max) 
    AND (
        ($birth_month > month_min OR ($birth_month = month_min AND $birth_day >= day_min)) 
        OR 
        ($birth_month < month_max OR ($birth_month = month_max AND $birth_day <= day_max))
    )
);
");
if ($user_data = mysqli_fetch_array($result)) {
    $sign_name = $user_data['sign_name'];
}
function timeAgo($timestamp) {
    // Convert the database timestamp to a Unix timestamp
    date_default_timezone_set('Asia/Singapore');
    $time = strtotime($timestamp);
    $currentTime = time();

    // Calculate the time difference in seconds
    $diff = $currentTime - $time;

    // Determine the output using a switch statement
    switch (true) {
        case ($diff < 60): // Less than 1 minute
            return "$diff seconds ago";

        case ($diff < 3600): // Less than 1 hour
            $minutes = floor($diff / 60);
            return "$minutes minutes ago";

        case ($diff < 86400): // Less than 1 day
            $hours = floor($diff / 3600);
            return "$hours hours ago";

        case ($diff < 604800): // Less than 1 week
            $days = floor($diff / 86400);
            return "$days days ago";

        case ($diff < 2629440): // Less than 1 month
            $weeks = floor($diff / 604800);
            return "$weeks weeks ago";

        case ($diff < 31553280): // Less than 1 year
            $months = floor($diff / 2629440);
            return "$months months ago";

        default: // 1 year or more
            $years = floor($diff / 31553280);
            return "$years years ago";
    }
}
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
        <div class="container my-5">
            <h1 class="text-center mb-4">My Zodiac Sign is: <?= $sign_name ?></h1>
            <div class="row g-4">
                <?=printZodiacCard('Aries')?>
                <?=printZodiacCard('Taurus')?>
                <?=printZodiacCard('Gemini')?>
                <?=printZodiacCard('Cancer')?>
                <?=printZodiacCard('Leo')?>
                <?=printZodiacCard('Virgo')?>
                <?=printZodiacCard('Libra')?>
                <?=printZodiacCard('Scorpius')?>
                <?=printZodiacCard('Sagittarius')?>
                <?=printZodiacCard('Capricornus')?>
                <?=printZodiacCard('Aquarius')?>
                <?=printZodiacCard('Pisces')?>
            </div>
        </div>
        <?=printZodiacDetails("Aries")?>
        <?=printZodiacDetails("Taurus")?>
        <?=printZodiacDetails("Gemini")?>
        <?=printZodiacDetails("Cancer")?>
        <?=printZodiacDetails("Leo")?>
        <?=printZodiacDetails("Virgo")?>
        <?=printZodiacDetails("Libra")?>
        <?=printZodiacDetails("Scorpius")?>
        <?=printZodiacDetails("Sagittarius")?>
        <?=printZodiacDetails("Capricornus")?>
        <?=printZodiacDetails("Aquarius")?>
        <?=printZodiacDetails("Pisces")?>
        <a class="btn btn-primary" href="logout.php" role="button">Logout</a>
    </body>
</html>

