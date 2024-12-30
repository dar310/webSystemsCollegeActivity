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
		exit();
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
  <link rel="stylesheet" href="styles/register.css">
  <style>
    /* Inline CSS for the horizontal gender layout */
    .gender-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .gender-container label {
      margin: 0;
    }
  </style>
  <script>
  window.onload = function () {
    document.getElementById("form").reset();
  };
</script>
</head>
<body>
<div class="wrapper">
	<div class="header">
		<h2>Registration Form</h2>
	</div>
	<form id="form" class="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<div class="form-control">
			<input type="text" placeholder="Enter First Name" id="firstname" name="firstname" required>
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="text" placeholder="Enter Last Name" id="lastname" name="lastname" required>
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="text" placeholder="Enter username" id="username" name="username" required>
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="date" placeholder="Enter birthday (dd/mm/yyyy)" id="birthday" name="birthday" required>
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="password" placeholder="Password" id="password" name="password" required>
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<input type="password" placeholder="Confirm Password" id="confirm" required>
			<i class="fas fa-check-circle"></i>
			<i class="fas fa-times-circle"></i>
			<small>Error message</small>
		</div>
		<div class="form-control">
			<label for="gender">Gender:</label>
			<div class="gender-container">
				<input type="radio" name="gender" id="male" value="male" required>
				<label for="male">Male</label>
				<input type="radio" name="gender" id="female" value="female" >
				<label for="female">Female</label>
				<input type="radio" name="gender" id="other" value="other" >
				<label for="other">Other</label>
			</div>
			<small>Error message</small>
		</div>
		<button type="submit" name="submit" id="submit">Register Now</button>
	</form>
</div>
<script>
    // JavaScript for validating password match
    const form = document.getElementById('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm');
    const submitButton = document.getElementById('submit');

    // Add event listener for form submission
    form.addEventListener('submit', function (e) {
        // Check if passwords match
        if (password.value !== confirmPassword.value) {
            e.preventDefault(); // Prevent form submission
            alert("Passwords do not match!");
        }
    });
</script>
</body>
</html>

