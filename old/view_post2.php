<?php
  session_start();
?>
<html>
<head>
  <title>IrishTrade</title>
<!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materializ
e/0.97.6/css/materialize.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js
"></script>
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/mate
rialize.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="styl
esheet">
<script>
    $(document).ready(function() {
      $('.modal-trigger').leanModal();
    });
  </script>


</head>
<body>

<nav class="blue darken-4">
    <div class="container">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo">IrishTrade</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
<?php
  if( empty($_SESSION['valid']) ) {
    //The user is not logged in
    print "<a href=\"#modal1\" class=\"modal-trigger\" data-target=\"#modal1\">Login</a> ";

    print "<a href=\"sign_up.php\">Sign Up</a>";
  } else {
    print "You are signed in as " .$_SESSION['name'];
    print "<a href=\"logout.php\">Logout</a> ";
  }
?>

	<!--li><a href="http://52.33.64.5:8161/new_user.php">Sign Up</a></li-->
      </ul>
    </div>
 <!--Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Login</h4>
      <form class="col s12" action="login.php" method="post">
      <div class="row">
        <div class="input-field col s12">
          <input id="email" name="username" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="password" name="password" type="password" class="validate">
          <label for="password">Password</label>
        </div>
      </div>
    <div class="modal-footer">
      <button type="submit" name="login" class=" modal-action modal-close waves-
effect waves-green btn-flat">Login</button>
    </div>
    </form>
    </div>
  </div>

    </div>
  </nav>



<?php
  $conn = oci_connect("guest", "guest", "xe");

  //If the user just posted a new comment
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorCount = 0;
    $post_id = $_GET['post_id'];
    $user_id = 1;
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
    print "<p><b>".$row['NAME']."</b> ".$row['TITLE'];
    if($row['FREE']) {
      print "FREE";
    } else {
      print "$".$row['PRICE'];
      if($row['BEST']) {
        print " or best offer";
      }
    }
    print "<br>".$row['POST_TIME']."<br>";
    print $row['DESCRIPTION']."<br>";
    print "Category: ".$row['CATEGORY']."<br>";
    print "Location: ".$row['LOCATION']."</p>";

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
      print '<form method = "POST" action = "'.htmlspecialchars($_SERVER['PHP_SELF']).'?post_id='.$_GET['post_id'].'">
         <table>
            <tr>
               <td>Comment:</td>
               <td><input type = "text" name = "comment">
               </td>
            </tr>

            <tr>
               <td>Anonymous:</td>
               <td><input type="checkbox" name = "anon">
               </td>
            </tr>

            <input type="hidden" name="post_id" value='.$_GET['post_id'].'>
            <tr>
               <td>
                  <input type = "submit" name = "submit" value = "Comment">
               </td>
            </tr>
         </table>
      </form>';
  }
  ?>
</body>
</html>
    
  
