<?php
  if(isset($_GET['post_id'])){
  $conn = oci_connect("guest", "guest", "xe");
  $lob = oci_new_descriptor($conn, OCI_D_LOB);

  $stmt = oci_parse ($conn, "begin image_pack.get_image_by_id(:postid, :image); end;");
  oci_bind_by_name($stmt, ':image', $lob, -1, OCI_B_BLOB);
  oci_bind_by_name($stmt, ':postid', $_GET['post_id']);
  oci_execute($stmt, OCI_DEFAULT);
  $result = $lob->load();

    // If any text (or whitespace!) is printed before this header is sent,
    // the text won't be displayed and the image won't display properly.
    // Comment out this line to see the text and debug such a problem.
  header("Content-type: image/JPEG");
  echo $result;

    oci_free_statement($stmt);
  } else {
    echo "Need a post_id in URL";
  }
?>
