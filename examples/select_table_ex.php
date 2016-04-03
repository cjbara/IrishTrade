<?php

  $conn = oci_connect("guest", "guest", "xe")
    or die("Couldn't connect");
?>

<form action="viewtable.php" method="post">
<?php
  select_data($conn);
?>
  <input type="submit" value="display table">
</form>

<?php
  function select_data($cn)
  {
    $stmt = oci_parse($cn, "select table_name from cat");
    oci_define_by_name($stmt, "TABLE_NAME", $tbl);
    oci_execute($stmt);
    print "Select the table to be viewed: ";
    print "<SELECT NAME=\"thetable\" SIZE=1>";
    while (oci_fetch($stmt)){
      print "<OPTION>".$tbl."\n";
    }
    print "</SELECT>\n";
  }
  oci_close($conn);
?>
