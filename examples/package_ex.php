<html>
<body>

<?php

  $conn = oci_connect("guest", "guest", "xe")
     or die("Couldn't connect");

  $id = 44;
  $query = "select salepack.getspname($id) as name,";
  $query .= " salepack.getcomm($id) as comm";
  $query .= " from dual";
  $stmt = oci_parse($conn, $query);
  oci_define_by_name($stmt, "NAME", $sp);
  oci_define_by_name($stmt, "COMM", $cm);
  oci_execute($stmt);
  oci_fetch($stmt);
  print "$sp's commission rate is $cm%<br/>";

  oci_close($conn);
?>

</body>
</html>
