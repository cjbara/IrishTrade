<?php
  if(isset($_GET['post_id'])){
  $conn = oci_connect("guest", "guest", "xe");

  $query = 'SELECT image FROM images WHERE post_id = 1';

  $stmt = oci_parse ($conn, $query);
  oci_execute($stmt, OCI_DEFAULT);
  $arr = oci_fetch_assoc($stmt);
  $result = $arr['IMAGE']->load();

  // If any text (or whitespace!) is printed before this header is sent,
  // the text won't be displayed and the image won't display properly.
  // Comment out this line to see the text and debug such a problem.
  header("Content-type: image/PNG");
  echo $result;


  oci_free_statement($stmt);
  } else {
    echo "Need a post_id in URL";
  }
?>
