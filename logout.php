<?php
   session_start();
   unset($_SESSION["valid"]);
   unset($_SESSION["user_id"]);
   unset($_SESSION["email"]);
   unset($_SESSION['fname']);
   unset($_SESSION['lname']);
   unset($_SESSION['name']);
   
   echo '<a href="index.php">Home</a><br>';
   echo 'You have successfully logged out';
   header('Location: index.php');
?>
