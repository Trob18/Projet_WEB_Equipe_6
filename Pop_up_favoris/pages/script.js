
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
        element2.innerText = "Retirer de la wishlist";
    }
    else {
        element.style.color = "grey";
        element2.innerText = "Ajouter à la wishlist";
    }
}

function create() {
    const element = document.getElementById("offres-global");
    const element2 = document.getElementById("annonce");
    const element3 = document.getElementById("creation");
    if (element) {
        element.style.display = "none"; // Cache l'élément ciblé
        element2.style.display = "none"; // Affiche l'élément ciblé
        element3.style.display = "flex"; // Affiche l'élément ciblé
    }
}

function toggleMessage1() {
    const checkbox1 = document.getElementById('checkbox1');
    const checkbox2 = document.getElementById('checkbox2');
    const element = document.getElementById("create-etudiant");
    const element2 = document.getElementById("create-pilote");
    if (checkbox1.checked) {
        checkbox2.checked = false;
        element2.style.display = "none"; // Cache l'élément ciblé
        element.style.display = "flex"; // Affiche l'élément ciblé
    } else { 
        element.style.display = "none";
    }
}

function toggleMessage2() {
    const checkbox1 = document.getElementById('checkbox1');
    const checkbox2 = document.getElementById('checkbox2');
    const element = document.getElementById("create-etudiant");
    const element2 = document.getElementById("create-pilote");
    if (checkbox2.checked) {
        checkbox1.checked = false;
        element.style.display = "none"; // Cache l'élément ciblé
        element2.style.display = "flex"; // Affiche l'élément ciblé
    } else {
        element2.style.display = "none";
    }
}



function Click() {
    let passwordField = document.getElementById("password1");
    let icon = document.getElementById("toggle-password");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.src = "https://cdn-icons-png.flaticon.com/512/158/158746.png"; // Icône œil barré
    } else {
        passwordField.type = "password";
        icon.src = "https://cdn-icons-png.flaticon.com/512/565/565655.png"; // Icône œil normal
    }
};



function open_wishlist(){
    const element = document.getElementById("wishlist");
    const element2 = document.getElementById("wishlist_header");
    const element3 = document.getElementById("wishlist_id");

    element2.style.border = "3px solid #004090";
    element2.style.display = "flex";
    element2.style.alignItems = "center";
    element2.style.gap = "10px";


    if (element) {
        element.style.display = "flex"; // Affiche l'élément ciblé
    }
    if (element3) {
        element3.style.display = "flex"; // Affiche l'élément ciblé
    }
};


function close_wishlist(){
    const element = document.getElementById("wishlist");
    if (element) {
        element.style.display = "none"; // Cache l'élément ciblé
    }
};