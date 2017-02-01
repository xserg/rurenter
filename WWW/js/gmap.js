
  
function addMarker(location) {
  marker = new google.maps.Marker({
    position: location,
    map: map
  });
    document.getElementById(\"lat\").value=marker.getPosition().lat();
    document.getElementById(\"lon\").value=marker.getPosition().lng();
  markersArray.push(marker);
}

// Removes the overlays from the map, but keeps them in the array
function clearOverlays() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
  }
}

// Shows any overlays currently in the array
function showOverlays() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(map);
    }
  }
}

// Deletes all markers in the array by removing references to them
function deleteOverlays() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
    markersArray.length = 0;
  }
}

function codeAddress() {
    var address = document.getElementById(\"address\").value;
    var full_adr='';
    if (geocoder) {
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);

          var marker = new google.maps.Marker({
              map: map, 
              position: results[0].geometry.location
          });

            for (i = 0; i < results[0].address_components.length; i++) {
                full_adr += '; '
                //+results[0].address_components[i][\"types\"]
                +results[0].address_components[i][\"long_name\"];

            }


            infowindow.setContent(full_adr);
            infowindow.open(map, marker);

            document.getElementById(\"address\").value = results[0].formatted_address+results[0].geometry.location;
        } else {
          alert(\"Geocode was not successful for the following reason: \" + status);
        }
      });
    }
  }