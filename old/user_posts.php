<?php
  require 'header.php';
?>

  <!-- Main structure -->
  <div class="container">
    <div class="row">
<?php
  $conn = oci_connect("guest", "guest", "xe");
  $curs = oci_new_cursor($conn);
  $query = "begin post_pack.get_posts_by_user(:cursor, :id); end;";
  $stmt = oci_parse($conn, $query);
  oci_bind_by_name($stmt, ":cursor", $curs, -1, OCI_B_CURSOR);
  oci_bind_by_name($stmt, ":id", $_SESSION['user_id']);
  oci_execute($stmt);

  oci_execute($curs);
  while(($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
?>
    <div class="col s6 m4">
          <div class="card medium blue darken-4">
            <div class="card-image">
              <img class="activator" height="100%" width="100%" src="view_image.php?post_id=<?php echo $row['POST_ID'];?>">
            </div>
            <div class="card-content white-text">
           <span><b><?php print $row['TITLE'];?></b></span>
              <p>
<?php
        if($row['FREE']) {
          print "FREE";
        } else {
          print "$".$row['PRICE'];
          if($row['BEST']) {
            print " or best offer";
          }
        }

        print "<br>Category: ".$row['CATEGORY']."<br>";
?>
            </div>
            <div class="card-action">
<?php
            print "<a href=\"view_post.php?post_id=".$row['POST_ID']."\"class=\"yellow-text\" >View Details</a>";
            if(!empty($_SESSION['valid'])){
             if($_SESSION['user_id'] == $row['USER_ID']) {
              print "<a href=\"edit_post.php?post_id=".$row['POST_ID']."\" class=\"yellow-text\">Edit Post</a>";
             }
            }
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
