<?php
    include_once("config.php");

    // Check if form submitted, insert form data into users table.
    if (isset($_POST['Submit'])) {
        $username = trim($_POST["username"]);
        $password = $_POST['password'];
        $first_name = $_POST["firstname"];
        $last_name = $_POST["lastname"];
        $birthday = $_POST["birthday"];
        $gender = $_POST["gender"];
        $address = $_POST['address'];
        $address_code = $_POST['address_code'];
        $is_admin = isset($_POST["admin"]) && $_POST["admin"] == '1' ? 1 : 0;

        if (empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($birthday) || empty($gender)) {
            echo '<script>alert("Please fill out all required fields.")</script>';
            exit;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into table
        $stmt = $conn->prepare("INSERT INTO users (username, first_name, last_name, password, birthday, gender, address, address_code, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $username, $first_name, $last_name, $password, $birthday, $gender, $address, $address_code, $is_admin);

        if ($stmt->execute()) {
            echo '<script>alert("User Successfully added")</script>';
        } else {
            echo '<script>alert("Failed to add user. Please try again.")</script>';
        }

        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles/add-edit-user.css">
    
</head>
<body>
    <div class="container">
        <h1>Add Users</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="form1">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" id="firstname" class="form-control" name="firstname" required>
            </div>

            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" id="lastname" class="form-control" name="lastname" required>
            </div>

            <div class="mb-3">
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date" id="birthday" class="form-control" name="birthday" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <div>
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
            </div>
            <div class="mb-3">
                <tr> 
                    <td><label class="form-label">Home Address</label></td>
                    <td><input type="text" class="form-control" name="address" required></td>
                </tr>
                <tr> 
                    <td><label class="form-label">Google Maps Address Code (only applicable if not pointed exactly)</label></td>
                    <td><input type="text" class="form-control" name="address_code" placeholder="ex. H288+H2 (points to Mapua Makati)"></td>
                </tr>
            </div>
            <div class="mb-3">
                <label class="form-label">Is Administrator?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="admin" id="not-admin" value="0" required>
                        <label class="form-check-label" for="not-admin">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="admin" id="is-admin" value="1">
                        <label class="form-check-label" for="is-admin">Yes</label>
                    </div>
                </div>
            </div>

            <button type="submit" name="Submit" class="btn btn-primary">Add User</button>
        </form>
        <br>
        <a href="admindashboard.php"><button class="btn btn-secondary">Go Back</button></a>
    </div>
</body>
</html>