<?php
  require 'header.php';

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
}


         }
         function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
         }
?>
<div class="container">
  <br>
  <form class="col s12" action="update_user.php" method="post">
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">person</i>
          <input id="fname" name="fname" type="text" class="validate" value="<?php echo $_SESSION['fname'];?>">
          <label for="fname">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="lname" name="lname" type="text" class="validate" value="<?php echo $_SESSION['lname'];?>">
          <label for="lname">Last Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">mail</i>
          <input id="email" name="email" type="email" class="validate" value="<?php echo $_SESSION['email'];?>">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class="material-icons prefix">phone</i>
          <input id="phone" name="phone" type="text" class="validate" value="<?php echo $_SESSION['phone'];?>">
          <label for="phone">Phone Number</label>
        </div>
      </div>
      <button type="submit" name="sign-up" class=" modal-action modal-close waves-effect waves-green btn">Update Information</button>
    </form>
</div>
<?php 
  require 'footer.php';
?>
