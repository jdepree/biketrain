<?php
include('utilities.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $startLabel = param('startLocation');
    $endLabel = param('endLocation');

} elseif ($method === 'GET') {

}