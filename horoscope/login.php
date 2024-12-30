<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    
    // Database credentials
    $host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $database = 'horoscope';
    
    // Connect to the database
    $conn = mysqli_connect($host, $db_username, $db_password, $database);
    
    // Check for errors
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
    
    // Check if user exists in database
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
   

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Retrieve hashed password from database
        $hashed_password = $row['password'];
        
        // Verify the password entered against the hashed password
        if (password_verify($_POST['password'], $hashed_password)) {
            if ($row["is_admin"] == 1) {
                $_SESSION['username'] = $username;
                $_SESSION['userid'] = $row["id"];
                header('Location: admindashboard.php');
            } else {
                $_SESSION['username'] = $username;
                $_SESSION['birth_month'] = $row["month"];
                $_SESSION['birth_day'] = $row["day"];
                header('Location: dashboard.php');
            }
        } else {
            echo "Invalid username or password."; // Wrong password
        }
    } else {
        echo "Invalid username or password."; // No matching username found
    }

// Close the database connection
mysqli_close($conn);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Horoscope Checker Section -->
    <div class="container d-flex flex-column justify-content-center align-items-center vh-100">
        <h1 class="mb-4">Horoscope Checker</h1>
        
        <!-- Login Form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="w-100" style="max-width: 400px;">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <!-- Separate Register Account Section -->
        <div class="mt-3 text-center">
            <label>Don't have an Account?</label>
            <a class="btn btn-outline-primary" href="register.php" role="button">Register</a>
        </div>
    </div>

</body>
</html>
