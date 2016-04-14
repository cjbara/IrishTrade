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
      $query = "insert into comments (user_id, post_id, text, anonymousornah) values ($user_id, $post_id, '$comment', $anon)";
      $stmt = oci_parse($conn, $query);
      oci_execute($stmt);
    }
  }

  $post_query = "select post_id, fname ||' '|| lname name, title, description, price,
timestamp post_time, category, orbestoffer best, location, free
from posts p, users u, categories
where p.user_id = u.user_id
and p.category_id = categories.category_id
and post_id = ".$_GET['post_id']."
order by post_time";
  $post = oci_parse($conn, $post_query);
  oci_execute($post);

  while( $row = oci_fetch_assoc($post) ) {
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
    $comment_query = "select u.fname || ' ' || u.lname name, c.text, timestamp comment_time, anonymousornah a
from comments c, users u
where c.post_id = ".$row['POST_ID']."
and c.user_id = u.user_id
order by comment_time";
    $comments = oci_parse($conn, $comment_query);
    oci_execute($comments);
    print "<p><b>Comments</b></p>";
    while( $comment = oci_fetch_assoc($comments) ) {
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
    
  
