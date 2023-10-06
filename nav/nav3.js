// Load the navigation using client-side include
fetch("/nav/nav3.html")
  .then(response => response.text())
  .then(data => {
    const placeholder = document.getElementById("navbar");
    placeholder.innerHTML = data;
  });

document.addEventListener("DOMContentLoaded", function() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "/php/get_coins.php", true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = xhr.responseText;
      document.getElementById("coinValue").textContent = response;
    } else {
      console.log("Error occurred while retrieving the coin value.");
    }
  };
  xhr.send();
});
