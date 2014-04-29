<?php
define ('DB_HOST', 'localhost');
define ('DB_USER', 'oar');
define ('DB_PASSWORD', 'gearshed');
define ('DB_DATABASE', 'biketrain');
define ('BASE_URL', 'http://localhost');
define ('ADMIN_EMAIL', 'admin@localhost');

function sendMailSend($receiver, $subject, $body, $from) {
	print "<P style='color:red;'>Sending mail to $receiver.  Subject: $subject.  Body: $body</P>";
	return 1; // For testing (comment for production).
	// For Production (uncomment): return mail($receiver, $subject, $body, "From: $from");
}

