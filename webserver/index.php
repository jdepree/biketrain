<?php
    require_once("utilities.php");
?>

<HTML>
<HEAD>
    <TITLE>Map of Bike Trains</TITLE>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places,geometry"></script>
</HEAD>
<BODY >
<?php if ($sessionId) { ?>
    <div id='dest-entry' style='margin:5px;'>
    <FORM method='post' action='rest/routes.php' >
    <input type='hidden' name='origin' value='browserRoute' />
    <input type='hidden' name='startLat' id='startLat' value='' /><input type='hidden' name='startLng' id='startLng' value='' />
    <input type='hidden' name='endLat' id='endLat' value='' /><input type='hidden' name='endLng' id='endLng' value='' />
    <input type='hidden' name='path' id='path' value='' /><input type='hidden' name='levels' id='levels' value='' />
    <input type='hidden' name='distance' id='distance' value='' />

    Where are you starting? <input type='text' name='startLocation' id='startLocation' style='width:300px;' />
    &nbsp;&nbsp;&nbsp;&nbsp;Where do you want to end up?  <input type='text' name='endLocation' id='endLocation' style='width:300px;' />
    &nbsp;&nbsp;&nbsp;&nbsp;<input type='button' name='saveRouteButton' id='saveRouteButton' value='Save Route' disabled onclick="submit();" />
     &nbsp;&nbsp;&nbsp;&nbsp;<a href='logout.php?origin=browserRoute'>Logout</a>
    </FORM>
        </div>
<?php } else { ?>
<div id='loginDiv' style='margin:5px;'>
<FORM method='post' action='index.php' >
<input type='hidden' name='origin' value='browserRoute' />
    <B>Login to Create a Bike Train Route!</B> &nbsp;&nbsp;&nbsp;&nbsp;
   Email: <input type='text' name='loginEmail' id='loginEmail' />
   &nbsp;&nbsp;&nbsp;&nbsp;Password:  <input type='password' name='loginPassword' id='loginPassword' />
   &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='loginButton' id='loginButton' value='Login' />
   &nbsp;&nbsp;&nbsp;&nbsp;Don't have an account? <a href='' onclick="document.getElementById('registerDiv').style.display='block'; return false;">Register Here!</a>
   <?php if ($badLogin) { print "<BR><FONT color='red'>Username or password not found</FONT>"; } ?>
</FORM>
</div>
<div id='registerDiv' style='margin:5px;display:none;' >
<FORM method='post' action='registerUser.php' >
<input type='hidden' name='origin' value='browserRoute' />
<P>Email: <input type='text' name='email' id='email' />
&nbsp;&nbsp;&nbsp;&nbsp;Password: <input type='password' name='password' id='password' />
&nbsp;&nbsp;&nbsp;&nbsp;Confirm Password: <input type='password' name='confirmPassword' id='confirmPassword' /></P>
<P>First Name:  <input type='text' name='firstName' id='firstName' />
&nbsp;&nbsp;&nbsp;&nbsp;Last Name: <input type='text' name='lastName' id='lastName' /></P>
<P>Facebook Username (optional): <input type='text' name='facebook' id='facebook' />
&nbsp;&nbsp;&nbsp;&nbsp;Who can see your name/Facebook? <SELECT name='displayLevel' id='displayLevel'><OPTION value='never'>No one</OPTION>
<OPTION value='friends'>Friends</OPTION><OPTION value='friendsOfFriends'>Friends of Friends</OPTION><OPTION value='everyone'>Everyone</OPTION></SELECT>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='registerButton' id='registerButton' value='Create My Account!' onclick="if (document.getElementById('password').value != document.getElementById('confirmPassword').value) { alert('Passwords don't match'); return false; } " /></P>
</FORM>
</div>
<?php } ?>
<div id='map-canvas' style='width:100%;height:90%;'></div>
<script>
var rendererOptions = {
  draggable: true
};
var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
var directionsService = new google.maps.DirectionsService();
var startMarker, endMarker;
var map;
var start = 0, finish = 0;

var atlanta = new google.maps.LatLng(33.7463, -84.384);

function initialize() {

  var mapOptions = {
    zoom: 11,
    center: atlanta
  };
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
  directionsDisplay.setMap(map);

  <?php

  function random_color_part() {
      return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
  }

  function random_color() {
      return random_color_part() . random_color_part() . random_color_part();
  }

    $result = doQuery("SELECT route_path FROM routes");
    while ($row = mysql_fetch_assoc($result)) {
    ?>
        decodedPath = google.maps.geometry.encoding.decodePath("<?php print $row['route_path']; ?>");
        newPolyline = new google.maps.Polyline({
             path: decodedPath,
             strokeColor: '#<?php print random_color(); ?>',
             strokeOpacity: 1.0,
             strokeWeight: 2
        });
        newPolyline.setMap(map);

    <?php
    }
  ?>
}

function calcRoute(pointA, pointB) {

  var request = {
    origin: pointA,
    destination: pointB,
    travelMode: google.maps.TravelMode.BICYCLING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
        startMarker.setVisible(false);
        endMarker.setVisible(false);
        saveRoute(directionsDisplay.getDirections());

        google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
            saveRoute(directionsDisplay.getDirections());
        });
    }
  });


}

function saveRoute(directions) {
    points = directions.routes[0].overview_path;
    encodedStr = google.maps.geometry.encoding.encodePath(points);
    encodedStr = encodedStr.replace(/\\/g,"\\\\");

    document.getElementById('path').value = encodeURIComponent(encodedStr);

    var total = 0;
    for (i = 0; i < directions.routes[0].legs.length; i++) {
        total += directions.routes[0].legs[i].distance.value;
    }
    document.getElementById("distance").value = total;

    document.getElementById('saveRouteButton').disabled = false;

}

<?php if ($sessionId) { ?>

var autocompleteStart = new google.maps.places.Autocomplete(document.getElementById('startLocation'));
var autocompleteFinish = new google.maps.places.Autocomplete(document.getElementById('endLocation'));

google.maps.event.addListener(autocompleteStart, 'place_changed', function() {
    var place = autocompleteStart.getPlace();

    startMarker = new google.maps.Marker({
          position: place.geometry.location,
          map: map
      });

    start = place.geometry.location;
    document.getElementById('startLat').value = start.lat();
    document.getElementById('startLng').value = start.lng();
    if (start && finish) {
        calcRoute(start, finish);
    }
});

google.maps.event.addListener(autocompleteFinish, 'place_changed', function() {
    var place = autocompleteFinish.getPlace();

    endMarker = new google.maps.Marker({
                 position: place.geometry.location,
                 map: map
             });

    finish = place.geometry.location;
    document.getElementById('endLat').value = finish.lat();
    document.getElementById('endLng').value = finish.lng();

    if (start && finish) {
        calcRoute(start, finish);
    }
});

<?php } ?>

google.maps.event.addDomListener(window, 'load', initialize);

</script>
</BODY>
</HTML>