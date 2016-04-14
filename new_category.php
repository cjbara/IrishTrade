<?php
   session_start();
?>

<html>

   <head>
      <style>
         .error {color: #FF0000;}
      </style>
   </head>

   <body>
<?php
  if( empty($_SESSION['valid']) ) {
    //The user is not logged in
    print "<a href=\"login.php\">Login</a> ";
    print "<a href=\"new_user.php\">Sign Up</a>";
  } else {
    print "<p>You are logged in as ".$_SESSION['name']."</p>";
    print "<a href=\"logout.php\">Logout</a> ";
    print "<a href=\"index.php\">Home</a> ";
  }

  $error = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if( empty($_POST['category']) ){
      $error = "Category is required";
    } else {
      $newCategory = $_POST['category'];
      $conn = oci_connect("guest", "guest", "xe");
      $stmt = oci_parse($conn, "select * from categories");
      oci_define_by_name($stmt, "CATEGORY", $c);
      oci_execute($stmt);
      while ($row = oci_fetch_assoc($stmt)){
        if( strtolower($row['CATEGORY']) == strtolower($newCategory) ) {
          $error = "This category already exists";
        }
      }
      if( $error == "" ){
        $query = "insert into categories (category, created_by) values ('$newCategory', ".$_SESSION['user_id'].")";
        $stmt = oci_parse($conn, $query);
        oci_execute($stmt);
      }
    }

  }
  $conn = oci_connect("guest", "guest", "xe");
  $stmt = oci_parse($conn, "select * from categories");
  oci_execute($stmt);
  print "<h3>Current Categories:</h3><ul>";
  while ($row = oci_fetch_assoc($stmt)){
    print "<li>".$row['CATEGORY']."</li>";
  }
  print "</ul>";
  
?>
      <form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
         <table>
            <tr>
               <td>New Category:</td>
               <td><input type = "text" name = "category">
                  <span class = "error"><?php echo $error;?></span>
               </td>
            </tr>
            <tr>
               <td>
                  <input type = "submit" name = "submit" value = "Submit">
               </td>
            </tr>
          </table>
      </form>
</body>
</html>
