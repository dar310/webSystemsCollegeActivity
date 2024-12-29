<?php

include_once("config.php");

// Check if form is submitted for user update, then redirect to homepage after update
if(isset($_POST['update']))
{	
	$id = $_POST['id'];
	
	$first_name=$_POST['first_name'];
	$last_name=$_POST['last_name'];
	$birthday=$_POST['birthday'];
	$gender=$_POST['gender'];
	$is_admin=$_POST['isAdmin'];
		
	// update user data
	$result = mysqli_query($conn, "UPDATE users SET first_name='$first_name',last_name='$last_name', birthday='$birthday',gender='$gender',is_admin='$is_admin' WHERE id=$id");
	
	// Redirect to homepage to display updated user in list
	header("Location: admindashboard.php");
}
?>
<?php
// Display selected user data based on id
// Getting id from url
$id = $_GET['id'];

// Fetch user data based on id
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");

while($user_data = mysqli_fetch_array($result))
{
	$first_name = $user_data['first_name'];
	$last_name = $user_data['last_name'];
	$birthday = $user_data['birthday'];
	$gender = $user_data['gender'];
	$is_admin = $user_data['is_admin'];
}
?>
<html>
	<head>	
		<title>Edit User Data</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <style>
             body {
            font-family: 'Nunito', sans-serif;
            color: white;
            background-image: linear-gradient(270deg, #51713A 0%, #000E21 100%);
        }

        .container {
            background-color: #202124;
            padding: 20px;
            border-radius: 10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
            max-width: 600px;
            margin: 50px auto;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn {
            width: 50%;
        }

        .form-check-label {
            color: white;
        }

        .btn-primary {
            background-color: #7ED6DF;
            border: none;
        }

        .btn-primary:hover {
            background-color: #6CC5C4;
        }
        </style>
	</head>

	<body>
        <div class="container">
            <a href="admindashboard.php" class="btn btn-primary mb-3">Home</a>
            <form name="update_user" method="post" action="edit-user.php">
                <table>
                    <tr>
                        <td><label class="form-label">First Name</label></td>
                        <td><input type="text" class="form-control" name="first_name" value="<?php echo $first_name;?>" required></td>
                    </tr>
                    <tr> 
                        <td><label class="form-label">Last Name</label></td>
                        <td><input type="text" class="form-control" name="last_name" value="<?php echo $last_name;?>" required></td>
                    </tr>
                    <tr> 
                        <td><label class="form-label">Birthday</label></td>
                        <td><input type="date" name="birthday" class="form-control" value=<?php echo $birthday;?> required></td>
                    </tr>
                    <tr> 
                        <td><label class="form-label">Gender</label></td>
                        <td>
                            <label>
                                <input type="radio" name="gender" class="form-check-input" value="male" 
                                <?php if($gender == 'male') echo 'checked'; ?> required> Male
                            </label>
                            <label>
                                <input type="radio" name="gender" class="form-check-input" value="female" 
                                <?php if($gender == 'female') echo 'checked'; ?>> Female
                            </label>
                            <label>
                                <input type="radio" name="gender" class="form-check-input" value="other" 
                                <?php if($gender == 'other') echo 'checked'; ?>> Other
                            </label>
                        </td>
                    </tr>
                    <tr> 
                        <td><label class="form-label">Is Administrator?</label></td>
                        <td>
                            <label>
                                <input type="radio" name="isAdmin" class="form-check-input" value="0" <?php if($is_admin == '0') echo 'checked'; ?> required>No
                            </label>
                            <label>
                                <input type="radio" name="isAdmin" class="form-check-input" value="1" <?php if($is_admin == '1') echo 'checked'; ?>>Yes
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="id" value=<?php echo $_GET['id'];?>></td>
                        <td><input type="submit" class="btn btn-primary" name="update" value="Update"></td>
                    </tr>
                </table>
            </form>
        </div>
	</body>
</html>
