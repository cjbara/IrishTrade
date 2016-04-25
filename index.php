<?php
  require 'header.php';
?>
  <!-- Main structure -->
  <div class="container">
    <div class="row">
<?php
  $conn = oci_connect("guest", "guest", "xe");
  $curs = oci_new_cursor($conn);
  $cat = false;
  $q = false;
  if(!empty($_GET['category'])) {
    $cat = $_GET['category'];
  }
  if(!empty($_GET['query'])) {
    $q = $_GET['query'];
  }
  if(!$cat && !$q){
    $query = "begin post_pack.get_all_posts(:posts_cursor); end;";
    $stmt = oci_parse($conn, $query);
  } else if (!$cat){
    $query = "begin post_pack.get_all_posts_query(:posts_cursor, :q); end;";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":q", $_GET['query']);
  } else if (!$q){
    $query = "begin post_pack.get_all_posts_category(:posts_cursor, :cat); end;";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":cat", $cat);
  } else {
    $query = "begin post_pack.get_all_posts_query_category(:posts_cursor, :q, :cat); end;";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":q", $_GET['query']);
    oci_bind_by_name($stmt, ":cat", $cat);
  }
  oci_bind_by_name($stmt, ":posts_cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stmt);

  oci_execute($curs);
  while(($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
?>
    <div class="col s4 m4">
    <div class="card medium">
      <div class="card-image waves-effect waves-block waves-light">
        <img class="activator" src="view_image.php?post_id=<?php echo $row['POST_ID'];?>">
      </div>
      <div class="card-content">
        <span class="card-title activator grey-text text-darken-4">
        <i class="material-icons right">more_vert</i>
<?php
        print $row['TITLE']."<br>";
        if($row['FREE']) {
          print "FREE";
        } else {
          print "$".$row['PRICE'];
          if($row['BEST']) {
            print " or best offer";
          }
        }
?>
        </span>
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
        print $row['DESCRIPTION']."<br>";
	$time = $row['TIMESTAMP'];
        print $row['TIMESTAMP']."<br>";
        print "Category: ".$row['CATEGORY']."<br>";
        print "Location: ".$row['LOCATION']."</p>";
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
