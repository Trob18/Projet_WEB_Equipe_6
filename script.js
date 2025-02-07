document.addEventListener("DOMContentLoaded", function () {
    const profileImg = document.getElementById("profile-img");
    const fileInput = document.getElementById("file-input");

    // Ouvrir le sélecteur de fichiers quand on clique sur l'image
    profileImg.addEventListener("click", function () {
        fileInput.click();
    });

    // Gérer le changement d'image après la sélection
    fileInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file && (file.type === "image/png" || file.type === "image/jpeg")) {
            const reader = new FileReader();

            reader.onload = function (e) {
                profileImg.src = e.target.result;
            };

            reader.readAsDataURL(file);
        } else {
            alert("Veuillez sélectionner un fichier image valide (PNG ou JPG).");
        }
    });
});