<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Zodiac Sign</title>
    <link rel="stylesheet" href="styles/register.css">
    <link rel="stylesheet" href="styles/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background-image: linear-gradient(270deg, #51713A 0%, #000E21 100%);
            color: white;
        }

        .container {
            background-color: #202124;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        label {
            color: white;
        }

        textarea {
            resize: none;
        }

        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Zodiac Sign</h1>
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

            function generateMonthOptions($selectedMonth = null) {
                global $months;
                $options = "<option value='' " . (empty($selectedMonth) ? 'selected' : '') . ">Select Month</option>";
                foreach ($months as $monthNumber => $monthName) {
                    $selected = ($monthNumber == $selectedMonth) ? 'selected' : '';
                    $options .= "<option value='$monthNumber' $selected>$monthName</option>";
                }
                return $options;
            }
        ?>
        <form action="add-zodiac.php" method="post">
            <div class="mb-3">
                <label for="sign_name" class="form-label">Zodiac Sign Name</label>
                <input type="text" id="sign_name" class="form-control" name="sign_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-control" name="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="daily_horoscope" class="form-label">Daily Horoscope</label>
                <textarea id="daily_horoscope" class="form-control" name="daily_horoscope"></textarea>
            </div>
            <div class="mb-3">
                <label for="min_date" class="form-label">First Range Month</label>
                <select class="form-select" name="min_month">
                    <?php echo generateMonthOptions(); ?>
                </select>
                <input type="text" id="min_day" class="form-control mt-2" name="min_day" maxlength="2" size="2" required>
                <p id="minErrorMessage" style="color: red; display: none;">Invalid day for the selected month!</p>
            </div>
            <div class="mb-3">
                <label for="max_date" class="form-label">Last Range Month</label>
                <select class="form-select" name="max_month">
                    <?php echo generateMonthOptions(); ?>
                </select>
                <input type="text" id="max_day" class="form-control mt-2" name="max_day" maxlength="2" size="2" required>
                <p id="maxErrorMessage" style="color: red; display: none;">Invalid day for the selected month!</p>
            </div>
            <div class="mb-3">
                <label for="image_path" class="form-label">Image Path</label>
                <input type="text" id="image_path" class="form-control" name="image_path" required>
            </div>
            <button type="submit" name="Submit" class="btn btn-primary">Add Zodiac Sign</button>
        </form>
        <br>
        <a href="admindashboard.php"><button class="btn btn-secondary">Go Back</button></a>
    </div>
    <?php

	// Check If form submitted, insert form data into users table.
	if(isset($_POST['Submit'])) {
        $sign_name = $_POST['sign_name'];
        $description = $_POST['description'];
        $daily_horoscope = $_POST['daily_horoscope'];
		$min_month = $_POST['min_month'];
		$min_day = $_POST['min_day'];
		$max_month = $_POST['max_month'];
		$max_day = $_POST['max_day'];
		$image_path = $_POST['image_path'];
		
		// include database connection file
		include_once("config.php");
				
		// Insert user data into table
		$result = mysqli_query($conn, "INSERT INTO zodiac(sign_name, month_min, month_max, day_min, day_max, description, daily_horoscope, image_path) VALUES ('$sign_name','$min_month','$max_month','$min_day','$max_day','$description','$daily_horoscope','$image_path')");
		
		// Show message when user added
		echo '<script>alert("Zodiac Sign Successfully added")</script>';
	}
	?>
</body>
</html>
