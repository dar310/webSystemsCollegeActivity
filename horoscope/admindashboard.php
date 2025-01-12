<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}
// Create database connection using config file
include_once("config.php");

function shortenText($text, $maxLength = 35) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    }
    return $text;
}
function convertNumtoMonth($month){
    switch($month){
        case 1: $month="January"; break;
        case 2: $month="February"; break;
        case 3: $month="March"; break;
        case 4: $month="April"; break;
        case 5: $month="May"; break;
        case 6: $month="June"; break;
        case 7: $month="July"; break;
        case 8: $month="August"; break;
        case 9: $month="September"; break;
        case 10: $month="October"; break;
        case 11: $month="November"; break;
        case 12: $month="December"; break;
        default: $month="N/A"; break;
    }
    return $month;
}         

// Fetch all users data from database
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$zodiac_result = mysqli_query($conn, "SELECT * FROM zodiac ORDER BY id_zodiac ASC");
$logged_in_user_id = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles/admin-dashboard.css">
</head>
<body class="bg-light">
    <div class="container py-5 bg-white shadow-sm rounded">
        <header class="text-center mb-4">
            <h1 class="text-primary">Administrator Dashboard</h1>
        </header>

        <div class="row">
            <!-- Users Table -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5">Users</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Birthday</th>
                                        <th>Gender</th>
                                        <th>Home Address</th>
                                        <th>Operations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                        while($user_data = mysqli_fetch_array($result)) {
                                            echo "<tr>";
                                            echo "<td>".$user_data['username']."</td>";
                                            echo "<td>".$user_data['last_name']."</td>";
                                            echo "<td>".$user_data['first_name']."</td>";
                                            echo "<td>".convertNumtoMonth($user_data['month'])." ".$user_data['day'].", ".$user_data['year']."</td>";    
                                            echo "<td>".ucfirst($user_data['gender'])."</td>";
                                            echo "<td>".$user_data['address']."</td>";
                                            if ($user_data['id'] == $logged_in_user_id) {
                                                // Disable the Edit and Delete buttons for the logged-in user
                                                echo "<td><span class='btn btn-warning btn-sm disabled'>Edit</span> <span class='btn btn-danger btn-sm disabled'>Delete</span></td>";
                                            } else {
                                                // Show the Edit and Delete buttons normally
                                                echo "<td><a href='edit-user.php?id=$user_data[id]' class='btn btn-warning btn-sm'>Edit</a> <a href='delete-user.php?id=$user_data[id]' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>";
                                            }
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="add-users.php" class="btn btn-success btn-block">Add New User</a>
                    </div>
                </div>
            </div>

            <!-- Zodiac Signs Table -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5">Zodiac Signs</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="zodiac-table">
                                <thead>
                                    <tr>
                                        <th>Zodiac Sign</th>
                                        <th>Description</th>
                                        <th>Daily Horoscope</th>
                                        <th>Month Range</th>
                                        <th>Image Path</th>
                                        <th>Operations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                        while($zodiac_data = mysqli_fetch_array($zodiac_result)) {
                                            echo "<tr>";
                                            echo "<td>".$zodiac_data['sign_name']."</td>";
                                            echo "<td>".shortenText($zodiac_data['description'])."</td>";
                                            echo "<td>".shortenText($zodiac_data['daily_horoscope'])."</td>";
                                            echo "<td>".convertNumtoMonth($zodiac_data['month_min'])." ".$zodiac_data['day_min']." - ".convertNumtoMonth($zodiac_data['month_max'])." ".$zodiac_data['day_max']."</td>";    
                                            echo "<td>".$zodiac_data['image_path']."</td>";    
                                            echo "<td><a href='edit-zodiac.php?id=$zodiac_data[id_zodiac]' class='btn btn-warning btn-sm'>Edit</a> <a href='delete-zodiac.php?id=$zodiac_data[id_zodiac]' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this zodiac sign?');\">Delete</a></td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="add-zodiac.php" class="btn btn-success btn-block">Add New Zodiac</a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center mt-4">
            <a href="logout.php" class="btn btn-danger">Log out</a>
        </footer>
    </div>
</body>
</html>