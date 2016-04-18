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
  if( $_SESSION['valid'] == false ) {
    //The user is not logged in
    print "<a href=\"login.php\">Login</a> ";
    print "<a href=\"new_user.php\">Sign Up</a>";
  } else {
    print "<p>You are logged in as ".$_SESSION['name']."</p>";
    print "<a href=\"logout.php\">Logout</a> ";
    print "<a href=\"index.php\">Home</a> ";
  }         

         // define variables and set to empty values
         $priceErr = $titleErr = $descErr = $categoryErr = $locationErr = "";
         $price = $title = $desc = $category_id = $location = $best = $free = "";
         
         if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $errorCount = 0;
            $user_id = $_SESSION['user_id'];

            if (empty($_POST["price"])) {
               $price = 0;
               $free = 1;
            }else {
               $price = test_input($_POST["price"]);
               $free = 0;
            }

            if (empty($_POST["best"])) {
               $best = 0;
            } else {
               $best = 1;
            }

            if (empty($_POST["title"])) {
               $titleErr = "Title is required";
               $errorCount++;
            }else {
               $title = test_input($_POST["title"]);
            }

            if (empty($_POST["desc"])) {
               $descErr = "Description is required";
               $errorCount++;
            }else {
               $desc = test_input($_POST["desc"]);
            }

            if (empty($_POST["category_id"])) {
               $categoryErr = "Category is required";
               $errorCount++;
            }else {
               $category_id = test_input($_POST["category_id"]);
            }
            
            if (empty($_POST["location"])) {
               $locationErr = "Location is required";
               $errorCount++;
            }else {
               $location = test_input($_POST["location"]);
            }
            $sold = 0;
            
            if( $errorCount == 0 ){
              $conn = oci_connect("guest", "guest", "xe");
              $query = "begin post_pack.new_post(:id, :user, :price, :obo, :title, :desc, :category, :location); end;";
              $stmt = oci_parse($conn, $query);
              oci_bind_by_name($stmt, ":id", $post_id);
              oci_bind_by_name($stmt, ":user", $user_id);
              oci_bind_by_name($stmt, ":price", $price);
              oci_bind_by_name($stmt, ":obo", $best);
              oci_bind_by_name($stmt, ":title", $title);
              oci_bind_by_name($stmt, ":desc", $desc);
              oci_bind_by_name($stmt, ":category", $category_id);
              oci_bind_by_name($stmt, ":location", $location);
              oci_execute($stmt);
            /*  if (isset($_FILES['image'])) {
                $lob = oci_new_descriptor($conn, OCI_D_LOB);
                $stmt = oci_parse($conn, 'insert into images (post_id, image) '
                       .'values (3, EMPTY_BLOB()) returning image into :IMAGE');
                oci_bind_by_name($stmt, ':IMAGE', $lob, -1, OCI_B_BLOB);
                oci_execute($stmt, OCI_DEFAULT);
                if ($lob->savefile($_FILES['image']['tmp_name'])) {
                  oci_commit($conn);
                }
                else {
                  echo "Couldn't upload Blob\n";
                }
                $lob->free();
              }*/
              //header("Location: view_post.php?post_id=$post_id");
            }
         }

         function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
         }
      ?>
		
      <h2>Create New Post</h2>
      
      <p><span class = "error">* required field.</span></p>
      
      <form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
         <table>
            <tr>
               <td>Post Title:</td>
               <td><input type = "text" name = "title">
                  <span class = "error">* <?php echo $titleErr;?></span>
               </td>
            </tr>
            
            <tr>
               <td>Price:</td>
               <td><input type="text" name = "price" onkeypress='return (event.charCode == 46) || (event.charCode >= 48 && event.charCode <= 57)' >
                  <span class = "error">* <?php echo $priceErr;?></span>
               </td>
            </tr>

            <tr>
               <td>Or Best Offer:</td>
               <td><input type="checkbox" name = "best">
               </td>
            </tr>

            <tr>
               <td>Description:</td>
               <td><input type = "text" name = "desc">
                  <span class = "error">* <?php echo $descErr;?></span>
               </td>
            </tr>
            
            <tr>
               <td>Location:</td>
               <td><input type = "text" name = "location">
                  <span class = "error">* <?php echo $locationErr;?></span>
               </td>
            </tr>

            <tr>
               <td>Category:</td>
               <td><select name = "category_id">
                     <?php 
                       $conn = oci_connect("guest", "guest", "xe");
                       $stmt = oci_parse($conn, "select * from categories");
                       oci_define_by_name($stmt, "CATEGORY", $c);
                       oci_execute($stmt);
                       while ($row = oci_fetch_assoc($stmt)){
                         print "<OPTION value=".$row['CATEGORY_ID'].">".$row['CATEGORY']."\n";
                       }
                     ?>
                  </select>
                  <span><a href="new_category.php">Add a new category</a></span>
                  <span class = "error">* <?php echo $categoryErr;?></span>
               </td>
            </tr>
            <tr>
               <td>Image:</td>
               <td><input type = "file" name = "image"></td>
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
