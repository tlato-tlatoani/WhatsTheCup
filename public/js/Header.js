document.addEventListener("DOMContentLoaded", () => {
  fetch("/WhatsTheCup/app/views/Header.php")
    .then(response => response.text())
    .then(data => {
      document.body.insertAdjacentHTML("afterbegin", data);
    });
});
