<?php
  session_start();

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

        if (empty($_POST["category"])) {
           $categoryErr = "Category is required";
           $errorCount++;
        }else {
           $category_id = test_input($_POST["category"]);
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
          print_r($_FILES);
          if (isset($_FILES['image'])) {
            echo "Image code";
            $lob = oci_new_descriptor($conn, OCI_D_LOB);
            $stmt = oci_parse($conn, 'insert into images (post_id, image) '
                   .'values (:post_id, EMPTY_BLOB()) returning image into :IMAGE');
            oci_bind_by_name($stmt, ':IMAGE', $lob, -1, OCI_B_BLOB);
            oci_bind_by_name($stmt, ':post_id', $post_id);
            oci_execute($stmt, OCI_DEFAULT);
            if ($lob->savefile($_FILES['image']['tmp_name'])) {
              oci_commit($conn);
            }
            else {
              echo "Couldn't upload Blob\n";
            }
            $lob->free();
          }
          header("Location: view_post.php?post_id=".$post_id);
        }
     }

     function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
     }
?>
