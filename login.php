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

               $query = "begin user_pack.check_login(:id, :email, :pw); end; ";
               $conn = oci_connect("guest", "guest", "xe");
               $info = oci_new_cursor($conn);
               $stmt = oci_parse($conn, $query);
               oci_bind_by_name($stmt, ':id', $user_id);
               oci_bind_by_name($stmt, ':email', $email);
               oci_bind_by_name($stmt, ':pw', $pw);
               oci_execute($stmt);
               if( $user_id >= 0 ) {
                 $query = "begin user_pack.get_user_info(:id, :info_cursor); end;";
                 $stmt = oci_parse($conn, $query);
                 oci_bind_by_name($stmt, ":info_cursor", $info, -1, OCI_B_CURSOR); 
                 oci_bind_by_name($stmt, ":id", $user_id); 
                 oci_execute($stmt);
                 oci_execute($info);
               
                 if( $row = oci_fetch_array($info) ) {
                  $_SESSION['valid'] = true;
                  $_SESSION['user_id'] = $row['USER_ID'];
                  $_SESSION['email'] = $row['EMAIL'];
                  $_SESSION['fname'] = $row['FNAME'];
                  $_SESSION['lname'] = $row['LNAME'];
                  $_SESSION['phone'] = $row['PHONENUMBER'];
                  $_SESSION['name'] = $row['FNAME']." ".$row['LNAME'];

                  echo 'You have entered valid user name and password';
                  header('Location: index.php');
                 }
               } else {
                  $msg = 'Wrong username or password';
                  header('Location: index.php?login=error');
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
			
   </body>
</html>


