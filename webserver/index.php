<?php
    require("utilities.php");
?>

<HTML>
<HEAD>
    <TITLE>Map of Bike Trains</TITLE>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
</HEAD>
<BODY >
<?php if ($sessionId) { ?>
    <div id='dest-entry' style='margin:5px;'>
    <B>Create a Bike Train Route!</B>&nbsp;&nbsp;&nbsp;&nbsp;
    Where are you starting? <input type='text' name='startLocation' id='startLocation' style='width:300px;' />
    &nbsp;&nbsp;&nbsp;&nbsp;Where do you want to end up?  <input type='text' name='endLocation' id='endLocation' style='width:300px;' />
    &nbsp;&nbsp;&nbsp;&nbsp;<input type='button' name='saveRouteButton' id='saveRouteButton' value='Save Route' disabled />
    </div>
<?php } else { ?>
<div id='loginDiv' style='margin:5px;'>
    <B>Login to Create a Bike Train Route!</B> &nbsp;&nbsp;&nbsp;&nbsp;
   Email: <input type='text' name='loginEmail' id='loginEmail' />
   &nbsp;&nbsp;&nbsp;&nbsp;Password:  <input type='password' name='loginPassword' id='loginPassword' />
   &nbsp;&nbsp;&nbsp;&nbsp;<input type='button' name='loginButton' id='loginButton' value='Login' />
   &nbsp;&nbsp;&nbsp;&nbsp;Don't have an account? <a href='' onclick="document.getElementById('registerDiv').style.display='block'; return false;">Register Here!</a>
</div>
<div id='registerDiv' style='margin:5px;display:none;' >
<P>Email: <input type='text' name='email' id='email' />
&nbsp;&nbsp;&nbsp;&nbsp;Password: <input type='password' name='password' id='password' />
&nbsp;&nbsp;&nbsp;&nbsp;Confirm Password: <input type='password' name='confirmPassword' id='confirmPassword' /></P>
<P>First Name:  <input type='text' name='firstName' id='firstName' />
&nbsp;&nbsp;&nbsp;&nbsp;Last Name: <input type='text' name='lastName' id='lastName' />
&nbsp;&nbsp;&nbsp;&nbsp;Facebook Username (optional): <input type='text' name='facebook' id='facebook' />
&nbsp;&nbsp;&nbsp;&nbsp;Who can see your name/Facebook? <SELECT name='displayLevel' id='displayLevel'><OPTION value='never'>No one</OPTION>
<OPTION value='friends'>Friends</OPTION><OPTION value='friendsOfFriends'>Friends of Friends</OPTION><OPTION value='everyone'>Everyone</OPTION></SELECT></P>
&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' name='registerButton' id='registerButton' value='Create My Account!' />
</div>
<?php } ?>
<div id='map-canvas' style='width:100%;height:100%;'></div>
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
    }
  });

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
    if (start && finish) {
        calcRoute(start, finish);
    }
});

<?php } ?>

google.maps.event.addDomListener(window, 'load', initialize);

</script>
</BODY>
</HTML>