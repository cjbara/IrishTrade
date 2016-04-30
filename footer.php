  <!-- Modal Structure -->
<?php
if( empty($_SESSION['valid']) ) {
?>
  </body>
</html>
<?php
} else {
?>
  <div id="messages-modal" class="modal bottom-sheet">
    <div class="modal-content">
      <h4>Messages</h4>
      <ul class="collection">
<?php
  //Get all message conversations and the most recent message
  $conn = oci_connect("guest", "guest", "xe");
  $curs = oci_new_cursor($conn);
  $query = "begin message_pack.get_message_conversation(:message_cursor, :userid); end;";
  $stmt = oci_parse($conn, $query);
  oci_bind_by_name($stmt, ":userid", $_SESSION['user_id']);
  oci_bind_by_name($stmt, ":message_cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stmt);
  
  oci_execute($curs);
  while(($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
?>
        <li class="collection-item avatar">
        <i class="material-icons circle">email</i>
        <span class="title">
<?php 
  if($row['SENDER_ID'] == $_SESSION['user_id']) { echo "To: ".$row['RECEIVER']; }
  else { echo "From: ".$row['SENDER']; }
?>
        </span>
        <p>
<?php 
        echo $row['TEXT'];
    if($row['READ'] == 1 && $row['SENDER'] == $_SESSION['name']) {
       echo '<div class="chip">
    READ by '.$row['RECEIVER'].'
    <i class="material-icons">close</i>
  </div>';
    }
        echo"<br>".$row['TIMESTAMP'];?></p>
<?php
    echo "<a href=\"messages.php?id=";
  if($row['SENDER_ID'] == $_SESSION['user_id']) { echo $row['RECEIVER_ID']; }
  else { echo $row['SENDER_ID']; }
    echo "\" class=\"secondary-content waves-effect waves-green btn\">View Conversation</a></li>";
  }
?>
      </ul>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>
</body>
</html>
<?php
}
?>
