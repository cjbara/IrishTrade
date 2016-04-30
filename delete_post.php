<?php
  require 'header.php';

  $post_id = $_GET['id'];
  $user_id = $_SESSION['user_id'];

  $conn = oci_connect("guest", "guest", "xe");
  $stmt = oci_parse($conn, "begin post_pack.delete_post(:post_id, :user_id); end;");

  oci_bind_by_name($stmt, ":post_id", $post_id);
  oci_bind_by_name($stmt, ":user_id", $user_id);
  oci_execute($stmt);

  header("Location: index.php");

  require 'footer.php';
?>
