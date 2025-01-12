<?php
session_start();
if (!isset($_SESSION['birth_month']) && !isset($_SESSION['birth_day']) && !isset($_SESSION['address'])) {
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
function createCalendarEmbed($birth_year, $birth_month, $birth_day) {
    // Format the date components with leading zeros
    $tmp_birth_month = str_pad($birth_month, 2, '0', STR_PAD_LEFT);
    $tmp_birth_day = str_pad($birth_day, 2, '0', STR_PAD_LEFT);
    
    $formatted_date = $birth_year . $tmp_birth_month . $tmp_birth_day;
    
    $base_url = "https://calendar.google.com/calendar/embed";
    $params = [
        'src' => 'lizziebuizon@gmail.com',
        'ctz' => 'Asia/Manila',
        'wkst' => '1',
        'bgcolor' => '%23ffffff',
        'color' => '%239E69AF',
        'showNav' => '1',
        'showTitle' => '0',
        'showPrint' => '0',
        'showTabs' => '0',
        'showTz' => '0',
        'showCalendars' => '0',
        'hl'=>'en',
        'mode' => 'Agenda',
        'showDate' => $formatted_date,
        'dates' => $formatted_date . '/' . $formatted_date
    ];
    
    $final_url = $base_url . '?' . http_build_query($params);
    
    return $final_url;
}
function printZodiacCard($zodiacSign) {
    global $conn;

    $query = "SELECT * FROM zodiac WHERE sign_name = '$zodiacSign'";
    $result = mysqli_query($conn, $query);
    $address = $_SESSION['address'];
    global $birth_month;
    global $birth_day;
    $tmp_birth_month = str_pad($birth_month, 2, '0', STR_PAD_LEFT);
    $tmp_birth_day = str_pad($birth_day, 2, '0', STR_PAD_LEFT);
    $birth_year = $_SESSION['birth_year'];
    $encodedAddress = urlencode($address);
    $apiKey= "AIzaSyBh7rC-rVEpyQbgPpHN3Ils9rY_JTBTIBo";
    $formatted_date = $birth_year . $tmp_birth_month . $tmp_birth_day;
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
        if(isset($_SESSION['home_address'])){
            $home_address = $_SESSION['home_address'];
        }
        else{
            $home_address = $address;
        }
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
                            <h2>' . $min_month . ' ' . $min_day . ' - ' . $max_month . ' ' . $max_day . '</h2><br><br>
                        </div>
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
            
            <!-- Description and Horoscope Card -->
            <div class="col-md-6">
                <div class="card h-100 shadow">
                    <div class="card-body d-flex flex-column">
                        <!-- Description Section -->
                        <iframe src="https://www.google.com/maps/embed/v1/place?key='.$apiKey.'&q='.$encodedAddress.'" style="border:0; width: 100%; height: 40%;"
                            frameborder="0" style="border:0" allowfullscreen></iframe>
                        <div class=mb-3>
                            <h4><br>Home Address:</h4>
                            <p>'.$home_address.'</p>
                        </div>
                        <h2><br>Calendar</h2>
                        <iframe
                            src="' . $final_url = createCalendarEmbed($birth_year, $birth_month, $birth_day) . '"
                            frameborder="0" 
                            scrolling="no"
                            style="border:0; width: 100%; height: 40%;">
                        </iframe>
                        <a class = "btn btn-primary"target="_blank" href="https://calendar.google.com/calendar/event?action=TEMPLATE&dates='.$formatted_date.'T120000Z/'.$formatted_date.'T130000Z">Create Event</a>
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
