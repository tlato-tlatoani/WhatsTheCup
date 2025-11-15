document.addEventListener("DOMContentLoaded", () => {
  fetch("/WhatsTheCup/app/views/SidebarAdmin.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("sidebar-container").innerHTML = data;
    });
});

