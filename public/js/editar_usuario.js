document.getElementById("file").addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById("foto-preview").src = e.target.result;
    };

    reader.readAsDataURL(file);
});

