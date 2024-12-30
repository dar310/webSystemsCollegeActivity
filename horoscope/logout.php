<?php
   session_start();
   unset($_SESSION["username"]);
   unset($_SESSION["password"]);
   $sessionMonthUnset=false;
   $sessionDayUnset=false;
   if (isset($_SESSION['birth_month'])){
      unset($_SESSION['birth_month']);
      $sessionMonthUnset = true;
   }
   if (isset($_SESSION['birth_day'])){
      unset($_SESSION['birth_day']);
      $sessionDayUnset = true;
   }
   if ($sessionDayUnset && $sessionMonthUnset){
      echo "Clearing Session<br>";
   }
   if (isset($_SESSION['userid'])){
      unset($_SESSION['userid']);
      echo "Clearing Admin Session<br>";
   }
   echo 'You logged out';
   header('Refresh: 1; URL = login.php');
?>