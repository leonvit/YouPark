<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Waiting for the driver...</title>
</head>
<body>
<div id="navbar"></div>
<script src="/nav/nav.js"></script><br>
<div class="text-center">
  <h1 class="text-center">Waiting for the driver...</h1>
  <div id="map" style="height: 400px;"></div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const destinationLat = urlParams.get("lat");
    const destinationLng = urlParams.get("lng");
    let map;
    let currentUserMarker;
    let otherUserMarker;
    let directionsService;
    let directionsDisplay;
    let mapCentered = false; // Flag to track if the map is centered

    // Load the Google Maps API asynchronously
    function loadGoogleMaps() {
        const script = document.createElement("script");
        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM&callback=initializeMap";
        script.async = true;
        document.body.appendChild(script);
    }

    function centerMapToMarkers() {
      const bounds = new google.maps.LatLngBounds();

      if (otherUserMarker) {
        bounds.extend(otherUserMarker.getPosition());
      }

      if (currentUserMarker) {
        bounds.extend(currentUserMarker.getPosition());
      }

      // Fit the map to the bounds
      map.fitBounds(bounds);
    }

    // Callback function to initialize the map after the API is loaded
    function initializeMap() {
        const meLat = parseFloat(destinationLat);
        const meLng = parseFloat(destinationLng);
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: meLat, lng: meLng },
            zoom: 15,
        });

        // Create a marker for the current user (Me)
        currentUserMarker = new google.maps.Marker({
            position: { lat: meLat, lng: meLng },
            map: map,
        });

        // Create a DirectionsService and a DirectionsDisplay object
        directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer();
        directionsDisplay.setMap(map);

        // Start updating the otherUserMarker (Other User)
        updateOtherUserMarker();
    }

    // Function to update the otherUserMarker and display directions
    function updateOtherUserMarker() {
        const destinationLat = urlParams.get("lat");
        const destinationLng = urlParams.get("lng");

        // Fetch the other user's location using fetch
        fetch("/php/get_live.php", {
            method: "POST",
            body: new URLSearchParams({ lat: destinationLat, lng: destinationLng }),
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        })
        .then(response => response.json())
        .then(data => {
            const userLat = parseFloat(data.user_lat);
            const userLng = parseFloat(data.user_long);

            // Create or update the otherUserMarker position (B marker)
            if (!otherUserMarker) {
                otherUserMarker = new google.maps.Marker({
                    position: { lat: userLat, lng: userLng },
                    map: map
                });
            } else {
                otherUserMarker.setPosition({ lat: userLat, lng: userLng });
            }

            // Center the map only if it hasn't been centered before
            if (!mapCentered) {
                centerMapToMarkers();
                mapCentered = true; // Set the flag to true after centering
            }

            // Display directions from otherUserMarker to currentUserMarker
            calculateAndDisplayRoute(userLat, userLng);
        })
        .catch(error => {
            console.error("Error fetching data: " + error);
        })
        .finally(() => {
            // Fetch data again every 2 seconds
            setTimeout(updateOtherUserMarker, 2000);
        });

    }

    // Calculate and display the route between two points
    function calculateAndDisplayRoute(userLat, userLng) {
        const currentUserLocation = new google.maps.LatLng(currentUserMarker.getPosition().lat(), currentUserMarker.getPosition().lng());
        const otherUserLocation = new google.maps.LatLng(userLat, userLng);

        const request = {
            origin: otherUserLocation, // Swap origin and destination
            destination: currentUserLocation,
            travelMode: google.maps.TravelMode.DRIVING
        };

        directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
            }
        });
    }

    // Load Google Maps API
    loadGoogleMaps();

    // Rest of your code
    function fetchData() {
        const urlParams = new URLSearchParams(window.location.search);
        const destinationLat = urlParams.get("lat");
        const destinationLng = urlParams.get("lng");
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/php/pairing1.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Check if the response is "success"
                if (xhr.responseText === "success") {
                    window.location.href = "/pages/go_success2.php?lat=" + destinationLat + "&lng=" + destinationLng;
                }
            }
        };

        // Serialize the data object as URL-encoded parameters
        var data = new URLSearchParams();
        data.append('lat', destinationLat);
        data.append('lng', destinationLng);

        xhr.send(data.toString());

        // Fetch data again after 1 second
        setTimeout(fetchData, 1000);
    }

    // Initial data fetch
    fetchData();
    </script>
  </div>
</body>
</html>
