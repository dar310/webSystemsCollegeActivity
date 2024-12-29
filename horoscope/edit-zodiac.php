<?php
    include_once("config.php");

    // Check if form is submitted for user update, then redirect to homepage after update
    if(isset($_POST['update']))
    {	
        $id = $_POST['id'];
        
        $sign_name = $_POST['sign_name'];
        $description = $_POST['description'];
        $daily_horoscope = $_POST['daily_horoscope'];
        $min_month = $_POST['min_month'];
        $min_day = $_POST['min_day'];
        $max_month = $_POST['max_month'];
        $max_day = $_POST['max_day'];
        $image_path = $_POST['image_path'];
            
        // update user data
        $result = mysqli_query($conn, "UPDATE zodiac SET sign_name='$sign_name',description='$description', daily_horoscope='$daily_horoscope',month_max='$max_month',day_max='$max_day',month_min='$min_month',day_min='$min_day',image_path='$image_path' WHERE id_zodiac=$id");
        
        // Redirect to homepage to display updated user in list
        header("Location: admindashboard.php");
    }
?>
<?php
// Display selected user data based on id
// Getting id from url
$id = $_GET['id'];

// Fetech user data based on id
$result = mysqli_query($conn, "SELECT * FROM zodiac WHERE id_zodiac=$id");

while($user_data = mysqli_fetch_array($result))
{
    $sign_name = $user_data['sign_name'];
    $description = $user_data['description'];
    $daily_horoscope = $user_data['daily_horoscope'];
    $min_month = $user_data['month_min'];
    $min_day = $user_data['day_min'];
    $max_month = $user_data['month_max'];
    $max_day = $user_data['day_max'];
    $image_path = $user_data['image_path'];
}
?>
<!DOCTYPE html>
<html>
	<head>	
		<title>Edit Zodiac Signs</title>
		<link rel="stylesheet" href="styles/register.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <style>
            textarea {
                resize: none;
            }
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
            width: 20%;
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
		<a href="admindashboard.php" class="btn btn-primary">Home</a>
		<br/><br/>
		<?php
            $months = [
                1 => "January",
                2 => "February",
                3 => "March",
                4 => "April",
                5 => "May",
                6 => "June",
                7 => "July",
                8 => "August",
                9 => "September",
                10 => "October",
                11 => "November",
                12 => "December"
            ];

            // Function to generate the <option> tags
            function generateMonthOptions($selectedMonth) {
                global $months;
                $options = "<option value='' " . (empty($selectedMonth) ? 'selected' : '') . ">Select Month</option>";
                foreach ($months as $monthNumber => $monthName) {
                    $selected = ($monthNumber == $selectedMonth) ? 'selected' : '';
                    $options .= "<option value='$monthNumber' $selected>$monthName</option>";
                }
                return $options;
            }
        ?>

		<form name="update_zodiac" method="post" action="edit-zodiac.php">
            <div class="mb-3">
                <table width="100%" border="0">
                    <tr>
                        <td><label class="form-label">Sign Name</label></td>
                        <td><input type="text" class="form-control" name="sign_name" value=<?php echo $sign_name;?> required></td>
                    </tr>
                    <tr> 
                        <td><label class="form-label">Description</label></td>
                        <td><textarea class="form-control" name="description" row=6><?php echo $description;?></textarea></td>
                    </tr>
                    <tr> 
                        <td><label class="form-label">Daily Horoscope</label></td>
                        <td><textarea name="daily_horoscope" class="form-control"><?php echo $daily_horoscope;?></textarea></td>
                    </tr>
                    <tr> 
                        <td><label for="min_month" class="form-label">Starting Month</label></td>
                        <td>
                            <select class="form-select" aria-label="Default select example" name="min_month">
                                <?php echo generateMonthOptions($min_month); ?>
                            </select>
                            <input type="text" id="min_day" class="form-control" name="min_day" maxlength="2" size="2" value=<?php echo $min_day;?> required>
                            <p id="minErrorMessage" style="color: red; display: none;">Invalid day for the selected month!</p>
                        </td>
                    </tr>
                    <tr> 
                        <td><label for="max_month" class="form-label">End Month</label></td>
                        <td><select class="form-select" aria-label="Default select example" name="max_month">
                                <?php echo generateMonthOptions($max_month); ?>
                            </select>
                            <input type="text" id="max_day" class="form-control" name="max_day" maxlength="2" size="2" value=<?php echo $max_day;?> required>
                            <p id="maxErrorMessage" style="color: red; display: none;">Invalid day for the selected month!</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="form-label">Image Path</label></td>
                        <td><input type="text" class="form-control" name="image_path" value=<?php echo $image_path;?> required></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="id" value=<?php echo $_GET['id'];?>></td>
                        <td><input type="submit" class="btn btn-primary"name="update" value="Update"></td>
                    </tr>
                </table>
            </div>
		</form>
	</body>
</html>

