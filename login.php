<?php
   session_start();
?>

<html>
   
   <head>
      <title>IrishTrade</title>
   </head>
	
   <body>
      <a href="index.php">Home</a>
      <h3>Enter Username and Password</h3> 
         
         <?php
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {


//query the database for the username / password combo
		
               $pw = $_POST['password'];
               $email = $_POST['username'];

               $query = "select user_id, phoneNumber, fname, lname from users where password = '$pw' and email = '$email' ";
               $conn = oci_connect("guest", "guest", "xe");
               $stmt = oci_parse($conn, $query);
               oci_execute($stmt);
               if( $user = oci_fetch_assoc($stmt) ){

                  $_SESSION['valid'] = true;
                  $_SESSION['user_id'] = $user['USER_ID'];
                  $_SESSION['email'] = $email;
                  $_SESSION['fname'] = $user['FNAME'];
                  $_SESSION['lname'] = $user['LNAME'];
                  $_SESSION['name'] = $user['FNAME']." ".$user['LNAME'];

                  echo 'You have entered valid user name and password';
                  echo '<a href="index.html">Home</a><br>';
                  header('Location: index.php');
               }else {
                  $msg = 'Wrong username or password';
               }
            }
         ?>
         <p>Guest account for testing purposes:<br>username: guest@nd.edu<br>password: guest</p>
         <form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
            <input type = "text" name = "username" placeholder = "email" 
               required autofocus></br>
            <input type = "password" name = "password" placeholder = "password" required>
            <button type = "submit" name = "login">Login</button>
         </form>
			
         <a href = "logout.php" tite = "Logout"> Click here to clean the session or logout of session.
   </body>
</html>


