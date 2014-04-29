<?php
require_once('utilities.php');

$email = param('email');
$password = param('password');
$firstName = param('firstName');
$lastName = param('lastName');
$facebook = param('facebook');
$displayLevel = param('displayLevel');
$origin = param('origin');

if ($email) {
    doQuery("INSERT INTO users (user_email, user_password, user_first_name, user_last_name, user_facebook_username, user_display_level) VALUES ('$email', MD5('$password'), '$firstName', '$lastName', '$facebook', '$displayLevel')");

    $_SESSION['session_user_id'] = $sessionId = mysql_insert_id();
    $_SESSION['session_first_name'] = $sessionFirstName = $firstName;
    $_SESSION['session_last_name'] = $sessionLastName = $lastName;
    $_SESSION['session_email'] = $sessionEmail = $email;
} else {
    print "Did not receive the desired parameters";
}

if ($origin == 'browserRoute') {
    include('index.php');
}
?>