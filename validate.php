<?php
   if(isset($_GET['error'])){
//===================== Login Error ================
    if($_GET['error'] == 'login'){
?>
     $('#login-modal').openModal();
     $('#login-error').addClass('card-panel red');
     $('#login-error-text').text('Invalid email or password');
     $('#login-modal #email').addClass('invalid');
     $('#login-modal #password').addClass('invalid');
<?php
//===================== Create new post Error ================
    } else if($_GET['error'] == 'create'){
?>
     $('#new-post-modal').openModal();
     $('#new-post-error').addClass('card-panel red');
     $('#new-post-error-text').text('Could not create post.');
     $('#new-post-modal #title').addClass('invalid');
     $('#new-post-modal #price').addClass('invalid');
     $('#new-post-modal #desc').addClass('invalid');
     $('#new-post-modal #location').addClass('invalid');
<?php
     if($_GET['error_type'] == 'title') {
       echo "$('#new-post-error-text').append(' You must have a title for your post.');";
     } else if($_GET['error_type'] == 'title_invalid') {
       echo "$('#new-post-error-text').append(' Your title must be under 30 characters.');";
     } else if($_GET['error_type'] == 'desc') {
       echo "$('#new-post-error-text').append(' You must have a description for your post.');";
     } else if($_GET['error_type'] == 'desc_invalid') {
       echo "$('#new-post-error-text').append(' Your description must be under 140 characters.');";
     } else if($_GET['error_type'] == 'category') {
       echo "$('#new-post-error-text').append(' You must have a category for your post.');";
     } else if($_GET['error_type'] == 'location') {
       echo "$('#new-post-error-text').append(' You must have a location for your post.');";
     } else if($_GET['error_type'] == 'location_invalid') {
       echo "$('#new-post-error-text').append(' Your location must be under 50 characters.');";
     } else if($_GET['error_type'] == 'price') {
       echo "$('#new-post-error-text').append(' Price must be a number.');";
     }
//===================== Sign Up Error ================
   } else if($_GET['error'] == 'signup') {
?>
     $('#sign-up-modal').openModal();
     $('#sign-up-error').addClass('card-panel red');
     $('#sign-up-error-text').text('Could not create new user.');
<?php
     if($_GET['error_type'] == 'fname') {
       echo "$('#sign-up-error-text').append(' You must enter a first name.');";
     } else if($_GET['error_type'] == 'fname_invalid') {
       echo "$('#sign-up-error-text').append(' Your first name must be 20 characters or less.');";
     } else if($_GET['error_type'] == 'lname') {
       echo "$('#sign-up-error-text').append(' You must enter a last name.');";
     } else if($_GET['error_type'] == 'lname_invalid') {
       echo "$('#sign-up-error-text').append(' Your last name must be 30 characters or less.');";
     } else if($_GET['error_type'] == 'pw') {
       echo "$('#sign-up-error-text').append(' You must enter a password and re-enter the same password.');";
     } else if($_GET['error_type'] == 'pw_invalid') {
       echo "$('#sign-up-error-text').append(' Your passwords did not match or they were too long');";
     } else if($_GET['error_type'] == 'phone') {
       echo "$('#sign-up-error-text').append(' You must enter a phone number.');";
     } else if($_GET['error_type'] == 'phone_invalid') {
       echo "$('#sign-up-error-text').append(' Your phone number must match the placeholder XXX-XXX-XXXX.');";
     } else if($_GET['error_type'] == 'email') {
       echo "$('#sign-up-error-text').append(' You must enter an @nd.edu email address.');";
     } else if($_GET['error_type'] == 'email_invalid') {
       echo "$('#sign-up-error-text').append(' Your email address was not a valid @nd.edu email address.');";
     }
   }

  }
?>
