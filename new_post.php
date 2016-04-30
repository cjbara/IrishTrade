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
           $price = $_POST["price"];
           if(!is_numeric($price)){
             $errorCount = 1;
             header("Location: index.php?error=create&error_type=price");
           }
           $free = 0;
        }

        if (empty($_POST["best"])) {
           $best = 0;
        } else {
           $best = 1;
        }

        if (empty($_POST["title"])) {
           header("Location: index.php?error=create&error_type=title");
           $titleErr = "Title is required";
           $errorCount++;
        }else {
           $title = $_POST["title"];
           if(strlen($title) > 30){
              header("Location: index.php?error=create&error_type=title_invalid");
              $errorCount = 1;
           }
        }

        if (empty($_POST["desc"])) {
           header("Location: index.php?error=create&error_type=desc");
           $descErr = "Description is required";
           $errorCount++;
        }else {
           $desc = $_POST["desc"];
           if(strlen($desc) > 140){
              header("Location: index.php?error=create&error_type=desc_invalid");
              $errorCount = 1;
           }
        }

        if (empty($_POST["category"])) {
           header("Location: index.php?error=create&error_type=category");
           $categoryErr = "Category is required";
           $errorCount++;
        }else {
           $category_id = $_POST["category"];
        }
        
        if (empty($_POST["location"])) {
           header("Location: index.php?error=create&error_type=location");
           $locationErr = "Location is required";
           $errorCount++;
        }else {
           $location = $_POST["location"];
           if(strlen($location) > 50){
              header("Location: index.php?error=create&error_type=location_invalid");
              $errorCount = 1;
           }
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
            $stmt = oci_parse($conn, 'begin image_pack.update_image(:id, :postid, :image); end;');
            oci_bind_by_name($stmt, ':image', $lob, -1, OCI_B_BLOB);
            oci_bind_by_name($stmt, ':postid', $post_id);
            oci_bind_by_name($stmt, ':id', $image_id);
            oci_execute($stmt, OCI_DEFAULT);
            if ($lob->savefile($_FILES['image']['tmp_name'])) {
              oci_commit($conn);
            }
            else {
              echo "Couldn't upload Blob\n";
              header("Location: view_post.php?post_id=".$post_id."&image=fail");
            }
            $lob->free();
          }
          header("Location: view_post.php?post_id=".$post_id);
        }
     }
?>
