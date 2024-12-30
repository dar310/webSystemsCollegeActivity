<?php
// Logout Script (logout.php)
header('HTTP/1.0 401 Unauthorized');
header('WWW-Authenticate: Basic realm="Restricted Area"'); // This will prompt the user to re-enter credentials

echo '<h1>You have been logged out</h1>';
header('Refresh: 1; URL = login.php');
exit;
?>
