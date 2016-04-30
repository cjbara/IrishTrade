<?php
  include 'header.php';

  if(isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errorCount = 0;
    $user_id = $_SESSION['user_id'];

        if (empty($_POST["sold"])) {
           $sold = 0;
        } else {
           $sold = 1;
        }

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
        
        if( $errorCount == 0 ){
          $conn = oci_connect("guest", "guest", "xe");
          $query = "begin post_pack.update_post(:id, :user, :price, :obo, :title, :desc, :category, :location, :sold); end;";
          $stmt = oci_parse($conn, $query);
          oci_bind_by_name($stmt, ":id", $post_id);
          oci_bind_by_name($stmt, ":user", $user_id);
          oci_bind_by_name($stmt, ":price", $price);
          oci_bind_by_name($stmt, ":obo", $best);
          oci_bind_by_name($stmt, ":title", $title);
          oci_bind_by_name($stmt, ":desc", $desc);
          oci_bind_by_name($stmt, ":category", $category_id);
          oci_bind_by_name($stmt, ":location", $location);
          oci_bind_by_name($stmt, ":sold", $sold);
          oci_execute($stmt);
          if ($_FILES['image']['size'] > 0) {
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
            }
            $lob->free();
          }
        }
?>
    <div class = "container">
    <div class="row">
      <div class="col s12 m12">
        <a href="view_post.php?post_id=<?php echo $post_id;?>">
        <div class="card-panel teal">
          <span class="white-text">You successfully updated your post! Click here to view it!</span>
        </div>
        </a>
      </div>
    </div>
    </div>
<?php
      }
    } else {
?>
    <div class = "container">
    <div class="row">
      <div class="col s12 m12">
        <div class="card-panel teal">
          <span class="white-text">You must have a post_id to edit a post!</span>
        </div>
      </div>
    </div>
    </div>
<?php
  }
     function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
     }

  $conn = oci_connect("guest", "guest", "xe");
  $stmt = oci_parse($conn, "begin post_pack.get_post_info(:id, :cursor); end;");
  $curs = oci_new_cursor($conn);

  oci_bind_by_name($stmt, ":id", $_GET['post_id']);
  oci_bind_by_name($stmt, ":cursor", $curs, -1, OCI_B_CURSOR);
  oci_execute($stmt);
  oci_execute($curs);
  if(($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
?>

<div class="container">

<div class="row">
  <div class="col s6 m6">
    <h4>Edit Post</h4>
  </div>
  <div class="col s6 m6"><br>
    <a href="delete_post.php?id=<?php print $_GET['post_id'];?>" class="waves-effect waves-red btn red">Delete This Post</a>
  </div>
</div>
      <form class="col s12" action="edit_post.php?post_id=<?php print $_GET['post_id'];?>" method="post" enctype="multipart/form-data">
    <div class="row">
   <div class="input-field col s12 m12">
     <p>
      <input type="checkbox" id="sold2" name="sold" <?php if($row['SOLD'] == 1){print " checked ";}?>>
      <label for="sold2">This product has been sold!</label>
     </p>
    </div>
  </div>
  <br>
      <div class="row">
        <div class="input-field col s12">
          <input id="title" name="title" type="text" class="validate" value="<?php echo $row['TITLE'];?>">
          <label for="title">Post Title</label>
        </div>
      </div>

  <div class="row">
    <div class="input-field col s12">
     <select name="category">
<?php
         $st = oci_parse($conn, "select category from categories where category_id = ".$row['category']);
         oci_execute($st);
         if($cat = oci_fetch_assoc($st)){
           print "<option value=\"\" disabled selected>".$cat['CATEGORY']."</option>";
         }
         $stmt = oci_parse($conn, "select * from categories order by category");
         oci_define_by_name($stmt, "CATEGORY", $c);
         oci_execute($stmt);
         while ($r = oci_fetch_assoc($stmt)){
           print "<OPTION value=".$r['CATEGORY_ID'].">".$r['CATEGORY']."</option>";
         }
?>
    </select>
    <label>Category</label>
  </div>
 </div>

<div class="row">
        <div class="input-field col s6">
          <input id="price" name="price" type="text" class="validate" value="<?php echo $row['PRICE'];?>" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46'>
          <label for="price">Price</label>
        </div>

   <div class="input-field col s6">
     <p>
      <input type="checkbox" id="best2" name="best" <?php if($row['BEST'] == 1){print " checked ";}?>>
      <label for="best2">Or Best Offer</label>
     </p>
    </div>
  </div>


      <div class="row">
        <div class="input-field col s12">
          <input id="desc" name="desc" type="text" class="validate" value="<?php echo $row['DESCRIPTION'];?>">
          <label for="desc">Description</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input id="location" name="location" type="text" class="validate" value="<?php echo $row['LOCATION'];?>">
          <label for="location">Location</label>
        </div>
      </div>

   <div class="row">
    <div class="file-field input-field">
      <div class="btn">
<?php
  $stmt = oci_parse($conn, "begin image_pack.image_exists_for_post(:postid, :imgid); end;");
  oci_bind_by_name($stmt, ":postid", $_GET['post_id']);
  oci_bind_by_name($stmt, ":imgid", $imgid);
  oci_execute($stmt);
  if($imgid == -1){
    echo "<span>Add New Image</span>";
  } else {
    echo "<span>Update Existing Image</span>";
  }
?>
        <input type="file" name="image">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
    </div>
<button type="submit" name="submit" class=" modal-action modal-close waves-effect waves-green btn-flat">Update Post</button>

</div>
<?php 
}
include 'footer.php'; 
?>
