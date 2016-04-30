<?php
  session_start();
?>
<html>
<head>
<title>IrishTrade</title>
 <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <script>
    $(document).ready(function() {
      $('.modal-trigger').leanModal();
      $('select').material_select();
      $('.progress').hide();

      //Insert the validate php script
      <?php include 'validate.php'; ?>

    });
    $('#upload').click(function() {
      $('.progress').show();
    });


  </script>
</head>
<body>
 <nav class="blue darken-4">
    <div class="container">
    <div class="nav-wrapper">
      <a href="index.php" class="brand-logo">IrishTrade</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
<?php
  if( empty($_SESSION['valid']) ) {
    //The user is not logged in
?>
	<li><a href="#login-modal" class="modal-trigger">Login</a></li>
	<li><a href="#sign-up-modal" class="modal-trigger">Sign Up</a></li>
<?php
  } else {
?>

    <li><a href="update_user.php">Welcome, <?php echo $_SESSION['name'];?></a></li>

    <li><a href="#search-modal" class="modal-trigger"> <i class="material-icons">search</i></a></li>


<li><a class="dropdown-button" href="#!" data-activates="dropdown1">Menu<i class="material-icons right">arrow_drop_down</i></a></li>

<?php
  }
?>
      </ul>
    </div>
    </div>
  </nav>


  <!--Dropdown Structure-->
  <ul id="dropdown1" class="dropdown-content">
      <li><a href="#new-post-modal" class="modal-trigger" data-target="#new-post-modal">Create New Post</a></li>
      <li class="divider"></li>
      <li><a href="index.php?user_posts=true">View your posts</a></li>
      <li class="divider"></li>
      <li><a href="#messages-modal" class="modal-trigger">Messages</a>
      <li class="divider"></li>
      <li><a href="update_user.php">Edit Profile</a></li>
      <li class="divider"></li>
      <li><a href="logout.php">Logout</a></li>
  </ul>




  <!-- Search Modal Structure -->
  <div id="search-modal" class="modal">
    <div class="modal-content">
      <h4>Search Posts</h4>
      <form class="col s12" action="index.php" method="get" id="search-form">
      <div class="row">
        <div class="input-field col s12">
          <input id="query" name="query" type="text">
          <label for="query">Keywords</label>
        </div>
      </div>

  <div class="row">
    <div class="input-field col s12">
     <select name="category">
      <option value="" disabled selected>Select a Category</option>
<?php
         $conn = oci_connect("guest", "guest", "xe");
         $stmt = oci_parse($conn, "select * from categories order by category");
         oci_define_by_name($stmt, "CATEGORY", $c);
         oci_execute($stmt);
         while ($row = oci_fetch_assoc($stmt)){
           print "<OPTION value=".$row['CATEGORY'].">".$row['CATEGORY']."</option>";
         }
?>
    </select>
    <label>Category</label>
  </div>
 </div>


    <div class="modal-footer">
      <button type="submit" class=" modal-action modal-close waves-effect waves-green btn-flat">Search</button>
    </div>
    </form>
    </div>
  </div>


  <!-- Create New Post Modal Structure -->
  <div id="new-post-modal" class="modal">
    <div class="modal-content">
      <h4>Create New Post</h4>
      <div id="new-post-error"><span id="new-post-error-text" class="white-text"></span></div>
      <form class="col s12" id="new-post-form" action="new_post.php" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="input-field col s12">
          <input id="title" name="title" type="text" length="30">
          <label for="title">Post Title</label>
        </div>
      </div>

  <div class="row">
    <div class="input-field col s12">
     <select name="category" id="category">
      <option value="" disabled selected>Select a Category</option>
<?php
         $conn = oci_connect("guest", "guest", "xe");
         $stmt = oci_parse($conn, "select * from categories order by category");
         oci_define_by_name($stmt, "CATEGORY", $c);
         oci_execute($stmt);
         while ($row = oci_fetch_assoc($stmt)){
           print "<OPTION value=".$row['CATEGORY_ID'].">".$row['CATEGORY']."</option>";
         }
?>
    </select>
    <label>Category</label>
  </div>
 </div>

  <div class="row">
        <div class="input-field col s6">
          <input id="price" name="price" type="text" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46'>
          <label for="price">Price</label>
        </div>

   <div class="input-field col s6">
     <p>
      <input type="checkbox" id="best" name="best" />
      <label for="best">Or Best Offer</label>
     </p>
    </div>
  </div>


      <div class="row">
        <div class="input-field col s12">
          <input id="desc" name="desc" type="text" length="140">
          <label for="desc">Description</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input id="location" name="location" type="text" length="50">
          <label for="location">Location</label>
        </div>
      </div>




   <div class="row">
    <div class="file-field input-field">
      <div class="btn">
        <span>Image</span>
        <input type="file" name="image">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path" type="text">
      </div>
    </div>



    <div class="modal-footer">
      <button type="submit" name="submit" class=" modal-action modal-close waves-effect waves-green btn-flat">Submit</button>
    </div>
    </form>
    </div>
  </div>
  </div>


  <!-- Login Modal Structure -->
  <div id="login-modal" class="modal">
    <div class="modal-content">
      <h4>Login</h4>
      <div id="login-error"><span id="login-error-text" class="white-text"></span></div>
      <form class="col s12" action="login.php" method="post">
      <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">person</i>
          <input id="email" name="username" type="text" >
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">vpn_key</i>
          <input id="password" name="password" type="password">
          <label for="password">Password</label>
        </div>
      </div>
    <div class="modal-footer">
      <button type="submit" name="login" class=" modal-action modal-close waves-effect waves-green btn-flat">Login</button>
    </div>
    </form>
    </div>
  </div>


  <!-- Sign Up Modal Structure -->
  <div id="sign-up-modal" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>Sign Up</h4>
      <div id="sign-up-error"><span id="sign-up-error-text" class="white-text"></span></div>
      <form class="col s12" action="new_user.php" method="post">
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">person</i>
          <input id="fname" name="fname" type="text" length="20">
          <label for="fname">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="lname" name="lname" type="text" length="30">
          <label for="lname">Last Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">mail</i>
          <input id="email" name="email" type="email" length="30">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class="material-icons prefix">phone</i>
          <input id="phone" name="phone" type="text" placeholder="XXX-XXX-XXXX" length="12">
          <label for="phone">Phone Number</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">vpn_key</i>
          <input id="password" name="pw" type="password" length="30">
          <label for="password">Password</label>
        </div>
        <div class="input-field col s6">
          <input id="password" name="pw2" type="password" length="30">
          <label for="password">Re-enter Password</label>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" name="sign-up" class=" modal-action modal-close waves-effect waves-green btn-flat">Sign Up</button>
    </div>
    </form>
    </div>
  </div>
