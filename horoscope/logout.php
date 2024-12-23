<?php
   session_start();
   unset($_SESSION["username"]);
   unset($_SESSION["password"]);
   if (isset($_SESSION['birth_month'])){
      unset($_SESSION['birth_month']);
      echo "Unsetted Birth Month<br>";
   }
   if (isset($_SESSION['birth_day'])){
      unset($_SESSION['birth_day']);
      echo "Unsetted Birth Day<br>";
   }
   
   echo 'You logged out';
   header('Refresh: 1; URL = login.php');
?>