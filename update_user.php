<?php
  require 'header.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error_type = "";
    $errorCount = 0;
    if (empty($_POST["fname"])) {
       $error_type = "fname";
       $errorCount = 1;
       $fnameErr = "FirstName is required";
    }else {
       $fname = $_POST["fname"];
       if(strlen($fname) > 20){
          $error_type = "fname_invalid";
          $errorCount = 1;
       }
    }

    if (empty($_POST["lname"])) {
       $error_type = "lname";
       $errorCount = 1;
       $lnameErr = "Last Name is required";
    }else {
       $lname = $_POST["lname"];
       if(strlen($lname) > 30){
          $error_type = "lname_invalid";
          $errorCount = 1;
       }
    }

    if (empty($_POST["phone"])) {
       $error_type = "phone";
       $errorCount = 1;
       $unameErr = "Phone Number is required";
    }else {
       $phone = $_POST["phone"];
       if(! preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)){
          $errorCount = 1;
          $error_type = "phone_invalid";
       }
    }

    if (empty($_POST["email"])) {
       $error_type = "email";
       $errorCount = 1;
       $emailErr = "Email is required";
    }else {
       $email = $_POST["email"];

       // check if e-mail address is well-formed
       $allowed = "nd.edu";
       $explodedEmail = explode('@', $email);
       $domain = array_pop($explodedEmail);
       if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && $domain == $allowed )) {
          $error_type = "email_invalid";
          $errorCount = 1;
          $emailErr = "Invalid email format";
       }
    }
    if($errorCount == 0) {
      //update fields
      //TODO 
      $id = $_SESSION['user_id'];
      $conn = oci_connect("guest", "guest", "xe");
      $fname_stmt = oci_parse($conn, "begin user_pack.update_fname(:id, :fname); end;");
      $lname_stmt = oci_parse($conn, "begin user_pack.update_lname(:id, :lname); end;");
      $email_stmt = oci_parse($conn, "begin user_pack.update_email(:id, :email); end;");
      $phone_stmt = oci_parse($conn, "begin user_pack.update_phone(:id, :phone); end;");
    
      oci_bind_by_name($fname_stmt, ":id", $id);
      oci_bind_by_name($fname_stmt, ":fname", $fname);
      oci_bind_by_name($lname_stmt, ":id", $id);
      oci_bind_by_name($lname_stmt, ":lname", $lname);
      oci_bind_by_name($email_stmt, ":id", $id);
      oci_bind_by_name($email_stmt, ":email", $email);
      oci_bind_by_name($phone_stmt, ":id", $id);
      oci_bind_by_name($phone_stmt, ":phone", $phone);
    
      oci_execute($fname_stmt);
      oci_execute($lname_stmt);
      oci_execute($email_stmt);
      oci_execute($phone_stmt);
    
      $_SESSION['fname'] = $fname;
      $_SESSION['lname'] = $lname;
      $_SESSION['email'] = $email;
      $_SESSION['phone'] = $phone;
      $_SESSION['name'] = $fname." ".$lname;
    } else {
     echo "<script>";
     echo "$(document).ready(function() {";
     echo "$('#update-user-error').addClass('card-panel red');";
     echo "$('#update-user-error-text').text('Could not update user information.');";
     if($error_type == 'fname') {
       echo "$('#update-user-error-text').append(' You must enter a first name.');";
     } else if($error_type == 'fname_invalid') {
       echo "$('#update-user-error-text').append(' Your first name must be 20 characters or less.');";
     } else if($error_type == 'lname') {
       echo "$('#update-user-error-text').append(' You must enter a last name.');";
     } else if($error_type == 'lname_invalid') {
       echo "$('#update-user-error-text').append(' Your last name must be 30 characters or less.');";
     } else if($error_type == 'phone') {
       echo "$('#update-user-error-text').append(' You must enter a phone number.');";
     } else if($error_type == 'phone_invalid') {
       echo "$('#update-user-error-text').append(' Your phone number must match the placeholder XXX-XXX-XXXX.');";
     } else if($error_type == 'email') {
       echo "$('#update-user-error-text').append(' You must enter an @nd.edu email address.');";
     } else if($error_type == 'email_invalid') {
       echo "$('#update-user-error-text').append(' Your email address was not a valid @nd.edu email address.');";
     }
     echo "});</script>";
    }
  }
?>
<div class="container">
  <div id="update-user-error"><span id="update-user-error-text" class="white-text"></span></div>
  <br>
  <form class="col s12" action="update_user.php" method="post">
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">person</i>
          <input id="fname" name="fname" type="text" value="<?php echo $_SESSION['fname'];?>" length="20">
          <label for="fname">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="lname" name="lname" type="text" value="<?php echo $_SESSION['lname'];?>" length="30">
          <label for="lname">Last Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">mail</i>
          <input id="email" name="email" type="text" value="<?php echo $_SESSION['email'];?>" length="30">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class="material-icons prefix">phone</i>
          <input id="phone" name="phone" type="text" value="<?php echo $_SESSION['phone'];?>" placeholder="XXX-XXX-XXXX" length="12">
          <label for="phone">Phone Number</label>
        </div>
      </div>
      <button type="submit" name="sign-up" class=" modal-action modal-close waves-effect waves-green btn">Update Information</button>
    </form>
</div>
<?php 
  require 'footer.php';
?>
