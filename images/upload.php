<?php
if (!isset($_FILES['image'])) {
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" 
   enctype="multipart/form-data">
Image filename: <input type="file" name="image">
<input type="submit" value="Upload">
</form>

<?php
}
else {

  $conn = oci_connect("guest", "guest", "xe");

  // Insert the BLOB from PHP's tempory upload area

  $lob = oci_new_descriptor($conn, OCI_D_LOB);
  $stmt = oci_parse($conn, 'insert into images (post_id, image) '
         .'values (1, EMPTY_BLOB()) returning image into :IMAGE');
  oci_bind_by_name($stmt, ':IMAGE', $lob, -1, OCI_B_BLOB);
  oci_execute($stmt, OCI_DEFAULT);

  // The function $lob->savefile(...) reads from the uploaded file.
  // If the data was already in a PHP variable $myv, the
  // $lob->save($myv) function could be used instead.
  if ($lob->savefile($_FILES['image']['tmp_name'])) {
    oci_commit($conn);
  }
  else {
    echo "Couldn't upload Blob\n";
  }
  $lob->free();
  oci_free_statement($stmt);

  // Now query the uploaded BLOB and display it

  $query = 'SELECT image FROM images WHERE post_id = 1';

  $stmt = oci_parse ($conn, $query);
  oci_execute($stmt, OCI_DEFAULT);
  $arr = oci_fetch_assoc($stmt);
  $result = $arr['IMAGE']->load();

  // If any text (or whitespace!) is printed before this header is sent,
  // the text won't be displayed and the image won't display properly.
  // Comment out this line to see the text and debug such a problem.
  header("Content-type: image/JPEG");
  echo $result;

  oci_free_statement($stmt);

  oci_close($conn); // log off
}
?>

