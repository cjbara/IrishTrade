<?php
  require 'header.php';
  $conn = oci_connect("guest", "guest", "xe");

  //If the user just posted a new comment
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorCount = 0;
    $post_id = $_GET['post_id'];
    $user_id = $_SESSION['user_id'];
    if (empty($_POST["comment"])) {
      $errorCount++;
    } else {
       $comment = $_POST["comment"];
    }

    if (empty($_POST["anon"])) {
      $anon = 0;
    } else { 
      $anon = 1;
    } 

    if( $errorCount == 0 ){
      $query = "begin comment_pack.new_comment(:id, :user, :post, :text, :anon); end;";
      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ":id", $comment_id);
      oci_bind_by_name($stmt, ":user", $user_id);
      oci_bind_by_name($stmt, ":post", $post_id);
      oci_bind_by_name($stmt, ":text", $comment);
      oci_bind_by_name($stmt, ":anon", $anon);
      oci_execute($stmt);
    }
  }

  //always diplay the post information
  $post_query = "begin post_pack.get_post_info(:post_id, :post_info); end;";
  $post = oci_parse($conn, $post_query);
  $post_cursor = oci_new_cursor($conn);
  oci_bind_by_name($post, ":post_id", $_GET['post_id']);
  oci_bind_by_name($post, ":post_info", $post_cursor, -1, OCI_B_CURSOR);
  oci_execute($post);
  oci_execute($post_cursor);

  while( $row = oci_fetch_array($post_cursor) ) {
?>
  <!-- Main structure -->
  <div class="container">
    <div class="row">
<?php

    print "<p><b>".$row['TITLE']."</b> ";
    print "</p>";
    print "<p><img src=\"view_image.php?post_id=".$row['POST_ID']."\"></p>";

    if($row['FREE']) {
      print "FREE";
    } else {
      print "$".$row['PRICE'];
      if($row['BEST']) {
        print " or best offer";
      }
    }
    print "</p><p>".$row['POST_TIME']."</p><p>";
    print $row['DESCRIPTION']."<br>";
    print "Category: ".$row['CATEGORY']."</p><p>";
    print "Location: ".$row['LOCATION']."</p>";


  if( !empty($_SESSION['valid']) ) {

?>



  <a href="#new-message-modal" class="modal-trigger waves-effect waves-green btn" data-target="#new-message-modal">Send Message to <?php print $row['NAME'];?></a>
  <!-- New Message Modal Structure -->
  <div id="new-message-modal" class="modal">
    <div class="modal-content">
      <h4>New Message to <?php print $row['NAME'];?></h4>
      <form class="col s12" action="messages.php?id=<?php print $row['USER_ID'];?>" method="post">
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix">mode_edit</i>
          <textarea id="message" name="message" class="materialize-textarea"></textarea>
          <label for="message">Message</label>
        </div>
      </div>
    <div class="modal-footer">
      <button type="submit" name="send" class=" modal-action modal-close waves-effect waves-green btn-flat">Send Message</button>
    </div>
    </form>
    </div>
  </div>

<?php
}
    //Display all comments for the post
    $comment_query = "begin comment_pack.comments_for_post(:post_id, :comment_info); end;";
    $comments = oci_parse($conn, $comment_query);
    $comment_cursor = oci_new_cursor($conn);
    oci_bind_by_name($comments, ":post_id", $_GET['post_id']);
    oci_bind_by_name($comments, ":comment_info", $comment_cursor, -1, OCI_B_CURSOR);
    oci_execute($comments);
    oci_execute($comment_cursor);

    print "<p><b>Comments</b></p>";
    while( $comment = oci_fetch_array($comment_cursor) ) {
      print "<p><b>";
      if( $comment['A'] ) {
        print "Anonymous";
      } else {
        print $comment['NAME'];
      }
      print "</b> ".$comment['TEXT']."<br>".$comment['COMMENT_TIME'];
    }
    oci_free_statement($comments);
  }
  oci_free_statement($post);
  oci_close($conn);
  if( !empty($_SESSION['valid']) ) {
      print '<form class="col s12" method = "POST" action = "'.htmlspecialchars($_SERVER['PHP_SELF']).'?post_id='.$_GET['post_id'].'">';
?>
      <input type="hidden" name="post_id" value='.$_GET['post_id'].'>
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">comment</i>
          <input id="comment" name="comment" type="text" class="validate">
          <label for="comment">Comment</label>
        </div>
        <div class="input-field col s2">
          <p>
             <input type="checkbox" name="anon" id="anon" />
             <label for="anon">Anonymous</label>
          </p>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s3">
        <button type="submit" name="submit" class=" modal-action modal-close waves-effect waves-green btn">Comment</button>
        </div>
      </div>
      </form>
<?php
  }
  require 'footer.php';
?>
