<?php
session_start();

require_once("configdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$reqVar = $_POST;
} else {
	$reqVar = $_GET;
}
getDBConn();

$sessionId = 0;
$badLogin = 0;
if (isset($_SESSION['session_user_id'])) {
    $sessionId = $_SESSION['session_user_id'];
    $sessionFirstName = $_SESSION['session_first_name'];
    $sessionLastName = $_SESSION['session_last_name'];
    $sessionEmail = $_SESSION['session_email'];
} elseif (hasParam('loginEmail')) {
    $email = param('loginEmail');
    $password = md5(param('loginPassword'));
    $result = doQuery("SELECT user_id, user_first_name, user_last_name, user_email FROM users WHERE user_email='$email' AND user_password='$password'");
    if ($row = mysql_fetch_assoc($result)) {
        $_SESSION['session_user_id'] = $sessionId = $row['user_id'];
        $_SESSION['session_last_name'] = $sessionLastName = $row['user_last_name'];
        $_SESSION['session_first_name'] = $sessionFirstName = $row['user_first_name'];
        $_SESSION['session_email'] = $sessionEmail = $row['user_email'];
    } else {
        $badLogin = 1;
    }
}

// DB functions
function getDBConn() {
        $db_link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        if (!$db_link) {
                die('Could not connect: ' . mysql_error());
        }
        mysql_select_db(DB_DATABASE);

        $now = new DateTime();
        $mins = $now->getOffset() / 60;
        $sgn = ($mins < 0 ? -1 : 1);
        $mins = abs($mins);
        $hrs = floor($mins / 60);
        $mins -= $hrs * 60;
        $offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
        mysql_query("SET time_zone='$offset';");

        return $db_link;
}

function doQuery($query) {
        global $_SESSION;

        $escaped = mysql_real_escape_string($query);
        //mysql_query("INSERT INTO log (log_record) VALUES ('$escaped')");
        print mysql_error();
        if (!($result = mysql_query($query))) {
                $email = '';
                $trace = '';
                $name = 'Unlogged User';
                if (isset($_SESSION['session_first_name'])) {
                        $name = $_SESSION['session_first_name'].' '.$_SESSION['session_last_name'];
                }

                //emailAdmin("Exception", "Exception caused by: $name\n\n".mysql_error()."\n\n".$query."\n\n".$trace);

                print "<FONT color='red'>Something has gone terribly wrong.  The administrator has been notified.  Please do not panic - you will be emailed as soon as the issue is resolved. ";
                if (isset($_SESSION['session_admin'])) {
                        print "<P>details: ".mysql_error()." <BR>QUERY: $query</FONT>";
                }
                print "Exception caused by: $name\n\n".mysql_error()."\n\n".$query."\n\n".$trace;
                die();
        }

        return $result;
}

// End DB functions

// Request processing

function param($key) {
        global $reqVar;
        return getFromReq($key, $reqVar);
}

function hasParam($key) {
        global $reqVar;
        return isset($reqVar[$key]);
}

function checked($key) {
        global $reqVar;
        return (isset($reqVar[$key]) ? 1 : 0);
}

function getGet($key) {
        return getFromReq($key, $_GET);
}

function getPost($key) {
        return getFromReq($key, $_POST);
}

function getFromReq($key, $req) {
        return mysql_real_escape_string(stripslashes($req[$key]));
}
// End request processing

?>
