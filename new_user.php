<html>
   
   <head>
      <style>
         .error {color: #FF0000;}
      </style>
   </head>
   
   <body> 
      <?php
         // define variables and set to empty values
         $fnameErr =$unameErr = $lnameErr = $emailErr = $pwErr = $phoneErr = $websiteErr = "";
         $fname = $lname = $uname = $phone = $email = $pw = "";
         $errorCount = 1;
         
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errorCount = 0;
            if (empty($_POST["fname"])) {
               $fnameErr = "FirstName is required";
            }else {
               $fname = test_input($_POST["fname"]);
            }

            if (empty($_POST["lname"])) {
               $lnameErr = "Last Name is required";
            }else {
               $lname = test_input($_POST["lname"]);
            }



            if (empty($_POST["pw"])) {
               $unameErr = "Password is required";
            }else {
               $pw = test_input($_POST["pw"]);
            }

            if (empty($_POST["phone"])) {
               $unameErr = "Phone Number is required";
            }else {
               $phone = test_input($_POST["phone"]);
            }
            
            if (empty($_POST["email"])) {
               $emailErr = "Email is required";
            }else {
               $email = test_input($_POST["email"]);
               
               // check if e-mail address is well-formed
               if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format"; 
               }
            }
            
         }
         
         function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
         }
      ?>
		
      <h2>Register New User</h2>
      
      <p><span class = "error">* required field.</span></p>
      
      <form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
         <table>
            <tr>
               <td>First Name:</td>
               <td><input type = "text" name = "fname">
                  <span class = "error">* <?php echo $fnameErr;?></span>
               </td>
            </tr>
            
            <tr>
               <td>Last Name:</td>
               <td><input type = "text" name = "lname">
                  <span class = "error">* <?php echo $lnameErr;?></span>
               </td>
            </tr>


            <tr>
               <td>E-mail: </td>
               <td><input type = "text" name = "email">
                  <span class = "error">* <?php echo $emailErr;?></span>
               </td>
            </tr>

            
            <tr>
               <td>Phone Number:</td>
               <td><input type = "text" name = "phone">
                  <span class = "error">* <?php echo $phoneErr;?></span>
               </td>
            </tr>

            <tr>
               <td>Password:</td>
               <td><input type = "password" name = "pw">
                  <span class = "error">* <?php echo $pwErr;?></span>
               </td>
            </tr>


            
            <tr>
               <td>
                  <input type = "submit" name = "submit" value = "Submit"> 
               </td>
            </tr>
            
         </table>
      </form>
      
      <?php
         echo "<h2>Your responses are:</h2>";
         echo ("<p>Your first name is $fname</p>");
         echo ("<p>Your last name is $lname</p>");
         echo ("<p> your email address is $email</p>");
         echo ("<p> your phone number is $phone</p>");
         echo ("<p> your password is $pw</p>");
         
      if($errorCount == 0){
        $insert = "insert into users (email, password, fname, lname, phonenumber) values ('$email', '$pw', '$fname', '$lname', '$phone')";
//        echo ("$insert");

        $conn = oci_connect("guest", "guest", "xe");
        $stmt = oci_parse($conn, $insert);
        oci_execute($stmt);


       }
      ?>
      
   </body>
</html>
