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
	<li><a href="#login-modal" class="modal-trigger" data-target="#login-modal">Login</a></li>
	<li><a href="#sign-up-modal" class="modal-trigger" data-target="#sign-up-modal">Sign Up</a></li>
<?php
  } else {
?>
    <li><a href="update_user.php">Welcome, <?php echo $_SESSION['name'];?></a></li>
    <li><a href="new_post.php">Create New Posting</a></li>
    <li><a href="#messages-modal" class="modal-trigger">Messages</a>
    <li><a href="logout.php">Logout</a></li>
<?php
  }
?>
      </ul>
    </div>
    </div>
  </nav>

  <!-- Login Modal Structure -->
  <div id="login-modal" class="modal">
    <div class="modal-content">
      <h4>Login</h4>
      <form class="col s12" action="login.php" method="post">
      <div class="row">
        <div class="input-field col s12">
          <input id="email" name="username" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="password" name="password" type="password" class="validate">
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
      <form class="col s12" action="new_user.php" method="post">
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">person</i>
          <input id="fname" name="fname" type="text" class="validate">
          <label for="fname">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="lname" name="lname" type="text" class="validate">
          <label for="lname">Last Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">mail</i>
          <input id="email" name="username" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
        <i class="material-icons prefix">phone</i>
          <input id="phone" name="phone" type="text" class="validate">
          <label for="phone">Phone Number</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">vpn_key</i>
          <input id="password" name="password" type="password" class="validate">
          <label for="password">Password</label>
        </div>
        <div class="input-field col s6">
          <input id="password" name="password" type="password" class="validate">
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
