<?php
    require_once('utilities.php');

    $sessionId = 0;
    session_destroy();
    if (param('origin') === 'browserRoute') {
        include('index.php');
    }
?>
