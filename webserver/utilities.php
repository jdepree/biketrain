<?php
session_start();

$sessionId = 0;
if (isset($_SESSION['userId'])) {
    $sessionId = $_SESSION['userId'];
}

?>
