<?php
define ('DB_HOST', 'your_db_server');
define ('DB_USER', 'your_db_username');
define ('DB_PASSWORD', 'your_db_password');
define ('DB_DATABASE', 'biketrain');
define ('BASE_URL', 'http://your-site-url');
define ('ADMIN_EMAIL', 'admin@your-site-url');

function sendMailSend($receiver, $subject, $body, $from) {
	print "<P style='color:red;'>Sending mail to $receiver.  Subject: $subject.  Body: $body</P>";
	return 1; // For testing (comment for production).
	// For Production (uncomment): return mail($receiver, $subject, $body, "From: $from");
}

