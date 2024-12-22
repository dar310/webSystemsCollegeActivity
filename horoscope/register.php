<?php

include_once("config.php");

// Check if the form was submitted
if (isset($_POST["submit"])) {
    // Retrieve the form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $birthday = $_POST["birthday"];
    $gender = $_POST["gender"];
    $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
    // Insert the user into the database
    $sql = "INSERT INTO users (username, first_name, last_name, password, birthday,gender, is_admin) VALUES ('$username','$firstname','$lastname','$password','$birthday','$gender','0')";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Registration Successful")</script>';
        header('Refresh: 1; URL = login.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="styles/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <h1>Register</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" id="firstname" class="form-control" name="firstname" required >
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" id="lastname" class="form-control" name="lastname" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" class="form-control" name="username" required pattern="^[a-zA-Z0-9-_]*$">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password"class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date" id="birthday" class="form-control" name="birthday" required>
            </div>
            <!-- Gender Radio Button -->
            <label class="form-check-label-gender" for="mb-3-gender">Gender:</label>
            <div class="mb-3-gender">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male-gender" value="male" required>
                    <label class="form-check-label" for="male-gender">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female-gender" value="female">
                    <label class="form-check-label" for="female-gender">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="other-gender" value="other">
                    <label class="form-check-label" for="other-gender">Other</label>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary" value="submit">Register Now</button>
        </form>
</body>
</html>
