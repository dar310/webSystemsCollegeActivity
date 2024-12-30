<?php
// Start session with secure settings
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Define authentication constants
define('AUTH_USERNAME', 'root');
define('AUTH_PASSWORD', '');

function clearSession() {
  session_destroy();
}

function isSessionValid($force_reauth = false) {
  global $_SESSION, $session_timeout;

  if (!isset($_SESSION['authenticated']) ||
      !isset($_SESSION['last_activity']) ||
      !isset($_SESSION['created_at'])) {
      return false;
  }

  $current_time = time();
  if (($current_time - $_SESSION['last_activity']) >= $session_timeout) {
      if ($force_reauth) {
          // Re-authenticate user before updating session
          echo "<h1>Authentication Required</h1>";
          header('Location: login.php');
          exit;
      }

      return false;
  }

  return true;
}

// Check session validity
if (!isset($_SESSION['authenticated'])) {
  clearSession();
  session_start();

  // Authentication
  if (!isset($_SERVER['PHP_AUTH_USER']) ||
      $_SERVER['PHP_AUTH_USER'] !== AUTH_USERNAME ||
      $_SERVER['PHP_AUTH_PW'] !== AUTH_PASSWORD) {
      header('HTTP/1.0 401 Unauthorized');
      header('WWW-Authenticate: Basic realm="Database Access Required"');
      echo "<h1>Database credentials required.</h1>";
      session_write_close();
      header('Location: login.php'); // Redirect properly
      exit;
  }

  // Regenerate session ID securely
  session_regenerate_id(true);
  $_SESSION['authenticated'] = true;
  $_SESSION['created_at'] = time();
  $_SESSION['last_activity'] = time();
}

// Update session activity
$_SESSION['last_activity'] = time();

// Check for session expiration
if ((time() - $_SESSION['last_activity']) >= 0.1 * 60) {
  echo "<h1>Session expired. Please re-authenticate.</h1>";
  header('Location: login.php'); // Redirect to login page
  exit;
}


// Rest of your registration handling code
if (isset($_POST["submit"])) {
    include_once("config.php");

    // Sanitize input data
    $firstname = mysqli_real_escape_string($conn, $_POST["firstname"]);
    $lastname = mysqli_real_escape_string($conn, $_POST["lastname"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $birthday = mysqli_real_escape_string($conn, $_POST["birthday"]);
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Use prepared statement for insertion
    $stmt = $conn->prepare("INSERT INTO users (username, first_name, last_name, password, birthday, gender, is_admin) VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("ssssss", $username, $firstname, $lastname, $password, $birthday, $gender);

    if ($stmt->execute()) {
        echo "<b>Registration successful!</b>";
        header('Refresh: 1; URL = login.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($conn);
}
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
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h2>Administrator Registration</h2>
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
  const form = document.getElementById('form');
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm');

  form.addEventListener('submit', function (e) {
      if (password.value !== confirm.value) {
          e.preventDefault(); // Prevent form submission
          alert('Passwords do not match!');
      }
  });
</script>
</body>
</html>
