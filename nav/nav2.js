// Load the navigation using client-side include
fetch("/nav/nav2.html")
  .then(response => response.text())
  .then(data => {
    const placeholder = document.getElementById("navbar");
    placeholder.innerHTML = data;
  });


