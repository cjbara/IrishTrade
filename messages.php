<?php
  require 'header.php';
$conn = oci_connect("guest", "guest", "xe");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorCount = 0;
    $id = $_GET['id'];
    $from = $_SESSION['user_id'];
    if (empty($_POST["message"])) {
      $errorCount++;
    } else {
       $message = $_POST["message"];
       if(strlen($message) > 140){
         $errorCount = 1;
       }
    }

    if( $errorCount == 0 ){
      $query = "begin message_pack.send_message(:id, :from, :id, :message); end;";
      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ":id", $message_id);
      oci_bind_by_name($stmt, ":from", $from);
      oci_bind_by_name($stmt, ":id", $id);
      oci_bind_by_name($stmt, ":message", $message);
      oci_execute($stmt);
    } else {
     echo "<script>";
     echo "$(document).ready(function() {";
     echo "$('#message-error').addClass('card-panel red');";
     echo "$('#message-error-text').text('Could not send message.');";
     echo "$('#message-error-text').append(' Your message was too long.');";
     echo "});</script>";
    }
  }
  $user_curs = oci_new_cursor($conn);
  $userstmt = oci_parse($conn, "begin user_pack.get_user_info(:id, :curs); end;");
  oci_bind_by_name($userstmt, ":id", $_GET['id']);
  oci_bind_by_name($userstmt, ":curs", $user_curs, -1, OCI_B_CURSOR);
  oci_execute($userstmt);
  oci_execute($user_curs);
  if(($other_user = oci_fetch_array($user_curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false ) {
    $other_user_name = $other_user['FNAME']." ".$other_user['LNAME'];
  }

  $curs = oci_new_cursor($conn);
  $stid = oci_parse($conn, "begin message_pack.get_messages_between_users(:message_cursor, :other, :user); end;");
  oci_bind_by_name($stid, ":other", $_GET['id']);
  oci_bind_by_name($stid, ":user", $_SESSION['user_id']);
  oci_bind_by_name($stid, ":message_cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stid);
  
  oci_execute($curs);
?>
<div class="container">
  <div class="row">
    <h4>Message Conversation with <?php print $other_user_name;?></h4>
  </div>
  <div id="message-error"><span id="message-error-text" class="white-text"></span></div>
  <div class="row">

 <ul class="collection col s12">
<?php
  while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    $title = $row['SENDER']." on ".$row['TIMESTAMP'];    
    $text = $row['TEXT'];
    if($row['READ'] == 1 && $row['SENDER'] == $_SESSION['name']) {
       $text .= '<div class="chip">
    READ by '.$row['RECEIVER'].'
    <i class="material-icons">close</i>
  </div>';
    }
?>
    <li class="collection-item avatar">
      <span class="title"><?php print $title?></span>
	<i class="material-icons circle">email</i>
        <p><?php print $text?>
        
      </p>
    </li>




<?php
  }

  oci_free_statement($stid);
  oci_free_statement($curs);
  oci_close($conn);

  if( !empty($_SESSION['valid']) ) {
      print '<form method = "POST" action = "'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'">';
?>
      <input type="hidden" name="post_id" value='.$_GET['id'].'>
      <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">message</i>
          <input id="m" name="message" type="text" length="140">
          <label for="m">New Message</label>
        </div>
      <div class = "col s1">&nbsp
      </div>
      <div class="row">
        <div class="input-field col s4">
        <button type="submit" name="submit" class=" modal-action modal-close waves-effect waves-green btn">Send Message</button>
        </div>
      </div>
      </form>
<?php
  }
 print "</div></div>";
  require 'footer.php';
?>


