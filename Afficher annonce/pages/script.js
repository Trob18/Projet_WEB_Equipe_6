/*
const test = document.getElementById("test");
const Connexion = document.getElementById("Connexion");
const test_buttonLink = document.getElementById("test_button");


test_buttonLink.addEventListener("click", () => {
    alert("test");
    test.classList.add("active");
    Connexion.classList.remove("active");
    });*/


function test() {
    //alert("test"); // Affiche une alerte avec le texte "test"
    const element = document.getElementById("test125");
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









// Get a reference to the button
//const btn = document.getElementById("bouton-voir");
//const main = document.getElementById("main-js");

// Add an event handler for the click event
//btn.addEventListener("click", myFunction);
/*
function test(){
main.classList.remove('active');
};*/

/*
function test(){
    const test_t = document.getElementById("main-js");
    test_t.remove();
}

window.onload = ok;*/