document.addEventListener("DOMContentLoaded", () => {
  fetch("SidebarUsuario.html")
    .then(response => response.text())
    .then(data => {
      document.getElementById("sidebar-container").innerHTML = data;
    });
});