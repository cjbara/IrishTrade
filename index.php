<?php
  require 'header.php';
?>
  <!-- Main structure -->
  <div class="container">
    <div class="row">
<?php
  $conn = oci_connect("guest", "guest", "xe");
  $curs = oci_new_cursor($conn);
  $query = "begin post_pack.get_all_posts(:posts_cursor); end;";
  $stmt = oci_parse($conn, $query);
  oci_bind_by_name($stmt, ":posts_cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stmt);
  
  oci_execute($curs);
  while(($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
?>
    <div class="col s4 m4">
    <div class="card">
      <div class="card-image waves-effect waves-block waves-light">
        <img class="activator" src="image.jpg">
      </div>
      <div class="card-content">
        <span class="card-title activator grey-text text-darken-4">
<?php
        print $row['TITLE'];
        if($row['FREE']) {
          print "FREE";
        } else {
          print "$".$row['PRICE'];
          if($row['BEST']) {
            print " or best offer";
          }
        }
?>
        <i class="material-icons right">more_vert</i></span>
        <p>
        <?php print "<a href=\"view_post.php?post_id=".$row['POST_ID']."\">Details</a>"; ?>
        </p>
      </div>
      <div class="card-reveal">
        <span class="card-title grey-text text-darken-4">
<?php
        print $row['TITLE'];
?>
        <i class="material-icons right">close</i></span>
        <p>
<?php
        print $row['DESCRIPTION'];
        print "<br>".$row['TIMESTAMP']."<br>";
        print $row['DESCRIPTION']."<br>";
        print "Category: ".$row['CATEGORY']."<br>";
        print "Location: ".$row['LOCATION']."</p>";
        print "<p>There are ".$row['NUMCOMMENTS']." comments on this post</p>";
?>
        </div>
      </div>
    </div>
<?php
  }
  print "</div>";

  oci_free_statement($stmt);
  oci_close($conn);
  require 'footer.php';
?>
