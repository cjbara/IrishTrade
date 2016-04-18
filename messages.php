<?php
  session_start();
?>
<html>
<head>
  <title>IrishTrade</title>
</head>
<body>
<?php
  if( empty($_SESSION['valid']) ) {
    //The user is not logged in
    print "<a href=\"login.php\">Login</a> ";
    print "<a href=\"new_user.php\">Sign Up</a>";
  } else {
    print "<p>You are logged in as ".$_SESSION['name']."</p>";
    print "<a href=\"logout.php\">Logout</a> ";
    print "<a href=\"index.php\">Home</a>";
  }
  $conn = oci_connect("guest", "guest", "xe");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorCount = 0;
    $to = $_GET['to'];
    $from = $_SESSION['user_id'];
    if (empty($_POST["message"])) {
      $errorCount++;
    } else {
       $message = $_POST["message"];
    }

    if( $errorCount == 0 ){
      $query = "begin message_pack.send_message(:id, $from, $to, '$message'); end;";
      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ":id", $message_id);
      oci_execute($stmt);
    }
  }

  $curs = oci_new_cursor($conn);
  $stid = oci_parse($conn, "begin message_pack.get_messages_between_users(:message_cursor,".$_GET['to'].",".$_SESSION['user_id']."); end;");
  oci_bind_by_name($stid, ":message_cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stid);
  
  oci_execute($curs);
  while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    print "<p>".$row['SENDER']." to ".$row['RECEIVER']." ".$row['TEXT']." ".$row['TIMESTAMP'] ."</p>";
  }
  oci_free_statement($stid);
  oci_free_statement($curs);
  oci_close($conn);

  if( !empty($_SESSION['valid']) ) {
      print '<form method = "POST" action = "'.htmlspecialchars($_SERVER['PHP_SELF']).'?to='.$_GET['to'].'">
         <table>
            <tr>
               <td>New Message:</td>
               <td><input type = "text" name = "message">
               </td>
            </tr>
            <input type="hidden" name="post_id" value='.$_GET['to'].'>
            <tr>
               <td>
                  <input type = "submit" name = "submit" value = "Send Message">
               </td>
            </tr>
         </table>
      </form>';
  }

  ?>
</body>
</html>
    
  
