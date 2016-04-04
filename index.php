<?php
  session_start();
?>
<html>
<head>
  <title>IrishTrade</title>
</head>
<body>
<?php
  if( empty($_SESSION['user_id']) ) {
    //The user is not logged in
    print "<a href=\"login.php\">Login</a> ";
    print "<a href=\"new_user.php\">Sign Up</a>";
  } else {
    print "<p>You are logged in as ".$_SESSION['name']."</p>";
    print "<a href=\"logout.php\">Logout</a> ";
    print "<a href=\"new_post.php\">Create New Posting</a>";
  }

  $conn = oci_connect("guest", "guest", "xe");
  $query = "select a.post_id, fname ||' '|| lname name, title, description, price, numComments, timestamp, category, orbestoffer best, location, free
from (
  select p.post_id, count(comment_id) numComments
  from posts p left outer join comments c
  on p.post_id = c.post_id
  group by p.post_id
) a, posts p, users u, categories
where p.user_id = u.user_id
and a.post_id = p.post_id
and p.category_id = categories.category_id
order by timestamp";
  $stmt = oci_parse($conn, $query);
  oci_execute($stmt);
  
  while( $row = oci_fetch_assoc($stmt) ) {
    
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
