<?php
   session_start();
   $_SESSION["valid"] = false;
   unset($_SESSION["user_id"]);
   unset($_SESSION["email"]);
   unset($_SESSION['fname']);
   unset($_SESSION['lname']);
   unset($_SESSION['name']);
   
   echo '<a href="index.html">Home</a><br>';
   echo 'You have successfully logged out';
   header('Refresh: 1; URL = index.php');
?>
