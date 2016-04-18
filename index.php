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
    print "<a href=\"new_post.php\">Create New Posting</a>";
  }

  $conn = oci_connect("guest", "guest", "xe");
  $curs = oci_new_cursor($conn);
  $query = "begin post_pack.get_all_posts(:posts_cursor); end;";
  $stmt = oci_parse($conn, $query);
  oci_bind_by_name($stmt, ":posts_cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stmt);
  
  oci_execute($curs);
  while(($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    
    print "<a href=\"view_post.php?post_id=".$row['POST_ID']."\">";
    print "<p><b>".$row['NAME']."</b> ".$row['TITLE']."</a> ";
    if($row['FREE']) {
      print "FREE";
    } else {
      print "$".$row['PRICE'];
      if($row['BEST']) {
        print " or best offer";
      }
    }
    print "<br>".$row['TIMESTAMP']."<br>";
    print $row['DESCRIPTION']."<br>";
    print "Category: ".$row['CATEGORY']."<br>";
    print "Location: ".$row['LOCATION']."</p>";
    print "<p>There are ".$row['NUMCOMMENTS']." comments on this post</p>";
  }

  oci_free_statement($stmt);
  oci_close($conn);
?>
</body>
</html>
