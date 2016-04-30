<?php
   session_start();
 // define variables and set to empty values
 $fnameErr =$unameErr = $lnameErr = $emailErr = $pwErr = $phoneErr = $websiteErr = "";
 $fname = $lname = $uname = $phone = $email = $pw = "";
 $errorCount = 1;
 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorCount = 0;
    if (empty($_POST["fname"])) {
       header("Location: index.php?error=signup&error_type=fname");
       $errorCount = 1;
       $fnameErr = "FirstName is required";
    }else {
       $fname = $_POST["fname"];
       if(strlen($fname) > 20){
          header("Location: index.php?error=signup&error_type=fname_invalid");
          $errorCount = 1;
       }
    }

    if (empty($_POST["lname"])) {
       header("Location: index.php?error=signup&error_type=lname");
       $errorCount = 1;
       $lnameErr = "Last Name is required";
    }else {
       $lname = $_POST["lname"];
       if(strlen($lname) > 30){
          header("Location: index.php?error=signup&error_type=lname_invalid");
          $errorCount = 1;
       }
    }

    if (empty($_POST["pw"]) || empty($_POST["pw2"])) {
       header("Location: index.php?error=signup&error_type=pw");
       $errorCount = 1;
       $unameErr = "Password is required";
    }else {
       if(( $_POST['pw'] != $_POST['pw2']) || (strlen($_POST['pw']) > 30)) {
         header("Location: index.php?error=signup&error_type=pw_invalid");
         $errorCount = 1;
       } else {
         $pw = $_POST["pw"];
       }
    }

    if (empty($_POST["phone"])) {
       header("Location: index.php?error=signup&error_type=phone");
       $errorCount = 1;
       $unameErr = "Phone Number is required";
    }else {
       $phone = $_POST["phone"];
       if(! preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)){
          $errorCount = 1;
          header("Location: index.php?error=signup&error_type=phone_invalid");
       }
    }
    
    if (empty($_POST["email"])) {
       header("Location: index.php?error=signup&error_type=email");
       $errorCount = 1;
       $emailErr = "Email is required";
    }else {
       $email = $_POST["email"];
       
       // check if e-mail address is well-formed
       $allowed = "nd.edu";
       $explodedEmail = explode('@', $email);
       $domain = array_pop($explodedEmail);
       if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && $domain == $allowed )) {
          header("Location: index.php?error=signup&error_type=email_invalid");
          $errorCount = 1;
          $emailErr = "Invalid email format"; 
       }
    }
    
      if($errorCount == 0){
        $insert = "begin user_pack.new_user(:id, :email, :pw, :first, :last, :phone); end;";
        $conn = oci_connect("guest", "guest", "xe");
        $stmt = oci_parse($conn, $insert);
        oci_bind_by_name($stmt, ":id", $user_id);
        oci_bind_by_name($stmt, ":email", $email);
        oci_bind_by_name($stmt, ":pw", $pw);
        oci_bind_by_name($stmt, ":first", $fname);
        oci_bind_by_name($stmt, ":last", $lname);
        oci_bind_by_name($stmt, ":phone", $phone);
        oci_execute($stmt);

        $_SESSION['valid'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
	$_SESSION['name'] = $fname." ".$lname;
        $_SESSION['phone'] = $phone;

        header("Location: index.php");
       }
     }
?>
