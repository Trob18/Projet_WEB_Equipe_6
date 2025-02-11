
function affichage() {
    const element = document.getElementById("offres-global");
    const element2 = document.getElementById("annonce");
        if (element) {
            element.style.display = "none"; // Cache l'élément ciblé
            element2.style.display = "flex"; // Affiche l'élément ciblé
}
}

function star() {
    const element = document.getElementById("star-button");
    const element2 = document.getElementById("star-text");
        if (element.style.color == "grey") {
            element.style.color = "#0056b3"; 
            element2.innerText = "Retirer de la wishlis";
        } 
        else {
            element.style.color = "grey";
            element2.innerText = "Ajouter a la wishlist"; 
        }
}



