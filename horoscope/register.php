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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css">
  <link rel="stylesheet" href="./style.css">
  <style>
    .gender-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .gender-container label {
      margin: 0;
    }
  </style>
</head>
<body>
<div class="wrapper">
	<div class="header">
		<h2>Registration Form</h2>
	</div>
	<form id="form" class="form">
		<div class="form-control">
			<input type="text" placeholder="Enter first name" id="name" />
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="email" placeholder="Enter last name" id="name" />
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="text" placeholder="Enter username" id="username" />
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="password" placeholder="Password" id="password" />
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		  <div class="mb-3">
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date" id="birthday" class="form-control" name="birthday" required>
            </div>
		<div class="form-control">
			<label for="gender">Gender:</label>
			<div class="gender-container">
				<input type="radio" name="gender" id="male" value="male" />
				<label for="male">Male</label>
				<input type="radio" name="gender" id="female" value="female" />
				<label for="female">Female</label>
				<input type="radio" name="gender" id="other" value="other" />
				<label for="other">Other</label>
			</div>
			<small>Error message</small>
		</div>
		<button type="submit">Submit</button>
	</form>
</div>
</body>
</html>
