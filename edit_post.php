<?php
  include 'header.php';

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
    <!--<a href="delete_post.php?id=<?php print $_GET['post_id'];?>" class="waves-effect waves-red btn red">Delete This Post</a>-->
  </div>
</div>
      <form class="col s12" action="edit_post.php?post_id=<?php print $_GET['post_id'];?>" method="post" enctype="multipart/form-data">
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
          <input id="price" name="price" type="text" class="validate" value="<?php echo $row['PRICE'];?>">
          <label for="price">Price</label>
        </div>

   <div class="input-field col s6">
     <p>
      <input type="checkbox" id="best2" name="best" <?php if($row['BEST'] == 1){print " checked ";}?>/>
      <label for="best">Or Best Offer</label>
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



<!-- 
   <div class="row">
    <div class="file-field input-field">
      <div class="btn">
        <span>Image</span>
        <input type="file" name="image">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
    </div>
-->
<button type="submit" name="submit" class=" modal-action modal-close waves-effect waves-green btn-flat">Update Post</button>

</div>
<?php 
}
include 'footer.php'; 
?>
