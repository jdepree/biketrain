<?php
require_once('../utilities.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $startLabel = param('startLocation');
    $startLat = param('startLat');
    $startLng = param('startLng');
    $endLat = param('endLat');
    $endLng = param('endLng');
    $endLabel = param('endLocation');
    $path = base64_encode(param('path'));
    $levels = param('levels');
    $distance = param('distance');

    $result = doQuery("SELECT loc_id FROM waypoints WHERE loc_lat = $startLat AND loc_lng = $startLng");
    if ($row = mysql_fetch_assoc($result)) {
        $startId = $row['loc_id'];
    } else {
        doQuery("INSERT INTO waypoints (loc_lat, loc_lng, loc_label) VALUES ($startLat, $startLng, '$startLabel')");
        $startId = mysql_insert_id();
    }

    $result = doQuery("SELECT loc_id FROM waypoints WHERE loc_lat = $endLat AND loc_lng = $endLng");
    if ($row = mysql_fetch_assoc($result)) {
        $endId = $row['loc_id'];
    } else {
        doQuery("INSERT INTO waypoints (loc_lat, loc_lng, loc_label) VALUES ($endLat, $endLng, '$endLabel')");
        $endId = mysql_insert_id();
    }

    doQuery("INSERT INTO routes (route_start_location, route_end_location, route_path, route_levels, route_distance, route_label, route_defined_by) VALUES ($startId, $endId, '$path', '$levels', $distance, '$startLabel -> $endLabel', $sessionId)");

    include('../index.php');
} elseif ($method === 'GET') {

}