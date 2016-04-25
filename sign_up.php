<?php
   session_start();
?>
<html>
<head>
<title>Testing Materialize signup</title>
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
      <a href="#" class="brand-logo">IrishTrade</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="#modal1" class="modal-trigger" data-target="#modal1">Login</a></li>
      </ul>
    </div>
    </div>
  </nav>


<div class="container">
 <div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">person</i>
          <input  id="first_name" type="text" class="validate">
          <label for="first_name">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="last_name" type="text" class="validate">
          <label for="last_name">Last Name</label>
        </div>
      </div>

    <div class="row">
        <div class="input-field col s12">
        <i class ="material-icons prefix">mail</i>
          <input id="email" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>

      <div class = row>
      <div class="input-field col s12">
          <i class="material-icons prefix">phone</i>
          <input id="icon_telephone" type="tel" class="validate">
          <label for="icon_telephone">Telephone</label>
        </div>
      </div>



    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
        <i class ="material-icons prefix">vpn_key</i>
          <input id="password1" type="password" class="validate">
          <label for="password1">Password</label>
        </div>
	<div class="input-field col s6">
          <input id="password2" type="password" class="validate">
          <label for="password2">Re-Enter Password</label>
        </div>
      </div>




    </form>
  </div>

</body>
</html>
