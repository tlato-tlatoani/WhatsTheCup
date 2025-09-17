

document.addEventListener("DOMContentLoaded", () => {
  fetch("Header.html")
    .then(response => response.text())
    .then(data => {
      document.body.insertAdjacentHTML("afterbegin", data);
    });
});
