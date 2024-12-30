<?php
session_start();
if (!isset($_SESSION['birth_month']) && !isset($_SESSION['birth_day'])) {
    header('Location: login.php');
    exit();
}

$birth_month = $_SESSION['birth_month'];
$birth_day = $_SESSION['birth_day'];
include_once("config.php");

function convertMonthNo($monthNum) {
    $months = [
        1 => "January", 2 => "February", 3 => "March", 4 => "April",
        5 => "May", 6 => "June", 7 => "July", 8 => "August",
        9 => "September", 10 => "October", 11 => "November", 12 => "December"
    ];
    return $months[$monthNum] ?? null;
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

function printZodiacCard($zodiacSign) {
    global $conn;

    $query = "SELECT * FROM zodiac WHERE sign_name = '$zodiacSign'";
    $result = mysqli_query($conn, $query);
    
    if ($zodiac_data = mysqli_fetch_array($result)) {
        $sign_name = htmlspecialchars($zodiac_data['sign_name']);
        $image_path = htmlspecialchars($zodiac_data['image_path']);
        $min_month = convertMonthNo($zodiac_data['month_min']);
        $max_month = convertMonthNo($zodiac_data['month_max']);
        $min_day = $zodiac_data['day_min'];
        $max_day = $zodiac_data['day_max'];
        $description = htmlspecialchars($zodiac_data['description']);
        $daily_horoscope = htmlspecialchars($zodiac_data['daily_horoscope']);
        $last_updated = htmlspecialchars($zodiac_data['last_updated']);
        
        echo '
        <div class="row justify-content-center align-items-stretch g-4">
            <!-- Image Card -->
            <div class="col-md-6">
                <div class="card h-100 shadow">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="text-center mb-4">
                            <img src="' . $image_path . '" class="img-fluid rounded" alt="' . $sign_name . ' Image">
                        </div>
                        <div class="text-center">
                            <h1 class="mb-0">Period:</h1>
                            <h2>' . $min_month . ' ' . $min_day . ' - ' . $max_month . ' ' . $max_day . '</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description and Horoscope Card -->
            <div class="col-md-6">
                <div class="card h-100 shadow">
                    <div class="card-body d-flex flex-column">
                        <!-- Description Section -->
                        <div class="mb-4">
                            <h4 class="mb-3">About ' . $sign_name . '</h4>
                            <p>' . $description . '</p>
                        </div>
                        
                        <!-- Horoscope Section -->
                        <div class="mb-4">
                            <h4 class="mb-3">Today\'s Horoscope</h4>
                            <p>' . $daily_horoscope . '</p>
                        </div>
                        
                        <!-- Push footer to bottom -->
                        <div class="mt-auto">
                            <hr>
                            <div class="text-muted small">
                                Last Updated: ' . timeAgo($last_updated) . '
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    } else {
        echo '<p>No details found for this zodiac sign.</p>';
    }
}

$result = mysqli_query($conn, "SELECT * 
FROM zodiac 
WHERE (
    (month_min < month_max OR (month_min = month_max AND day_min <= day_max)) 
    AND (
        ($birth_month > month_min OR ($birth_month = month_min AND $birth_day >= day_min)) 
        AND 
        ($birth_month < month_max OR ($birth_month = month_max AND $birth_day <= day_max))
    )
) 
OR (
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles/dashboard.css">
    <script src = "scripts/dateScript.js"defer></script>
</head>
<body>
    <div class="container my-5">
        <a class="btn btn-primary" href="logout.php" id ="logout-btn"role="button">Logout</a>
        <h1 class="text-center mb-4 zodiac-heading">My Zodiac Sign<br></h1>
        <h2 class="text-center mb-4 zodiac-heading"><div class="date-container" id="current-date"></div></h2>
        <div class="row g-4">
            <?php
            printZodiacCard($sign_name);
            ?>
        </div>
    </div>
</body>
</html>
