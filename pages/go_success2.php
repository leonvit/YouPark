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
  <div class="container-fluid d-flex flex-column justify-content-center align-items-center vh-100">
    <h1 class="text-center">Waiting for Parking...</h1>
    <img src="https://i.gifer.com/origin/34/34338d26023e5515f6cc8969aa027bca_w200.gif" alt="Waiting for the driver" class="img-fluid">
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>

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
        
            window.location.href = "/";
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


</body>
</html>
