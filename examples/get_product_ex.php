<?php

  $conn = oci_connect("guest", "guest", "xe")
     or die("Couldn't connect");

  $query = "select p.prod_desc pd, m.manufactr_name mn";
  $query .= " from product p, manufacturer m";
  $query .= " where p.manufactr_id = m.manufactr_id";
  $stmt = oci_parse($conn, $query);
  oci_define_by_name($stmt, "PD", $pd);
  oci_define_by_name($stmt, "MN", $mn);
  oci_execute($stmt);
  while(oci_fetch($stmt)) {
    print "the $pd is made by $mn<br>";
  }

  oci_close($conn);
?>
