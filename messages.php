<?php
  require 'header.php';
  print "<div class=\"container\"><div class=\"row\">";
$conn = oci_connect("guest", "guest", "xe");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorCount = 0;
    $id = $_GET['id'];
    $from = $_SESSION['user_id'];
    if (empty($_POST["message"])) {
      $errorCount++;
    } else {
       $message = $_POST["message"];
    }

    if( $errorCount == 0 ){
      $query = "begin message_pack.send_message(:id, $from, $id, '$message'); end;";
      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ":id", $message_id);
      oci_execute($stmt);
    }
  }

  $curs = oci_new_cursor($conn);
  $stid = oci_parse($conn, "begin message_pack.get_messages_between_users(:message_cursor,".$_GET['id'].",".$_SESSION['user_id']."); end;");
  oci_bind_by_name($stid, ":message_cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stid);
  
  oci_execute($curs);
?>
 <ul class="collection">
<?php
  while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    $title = $row['SENDER']." to ".$row['RECEIVER']." on ".$row['TIMESTAMP'];    
    $text = $row['TEXT'];
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
        <div class="input-field col s6">
        <i class ="material-icons prefix">comment</i>
          <input id="m" name="message" type="text" class="validate">
          <label for="m">New Message</label>
        </div>
      <div class="row">
        <div class="input-field col s3">
        <button type="submit" name="submit" class=" modal-action modal-close waves-effect waves-green btn">Send Message</button>
        </div>
      </div>
      </form>
<?php
  }
 print "</div></div>";
  require 'footer.php';
?>


