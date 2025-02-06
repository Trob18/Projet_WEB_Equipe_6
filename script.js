const Connexion = document.getElementById('Connexion');
const Type_Creation = document.getElementById('Type_Creation');
const showCreationLink = document.getElementById('showCreation');
const showConnexionLink = document.getElementById('showConnexion');




// Afficher le formulaire de création de compte
showCreationLink.addEventListener('click', () => {
    Connexion.classList.remove('active');
    Type_Creation.classList.add('active');
  });
  
  // Afficher le formulaire de connexion
  showConnexionLink.addEventListener('click', () => {
    Type_Creation.classList.remove('active');
    Connexion.classList.add('active');
  });




  // Sélection des éléments nécessaires
const etudiant = document.getElementById('etudiant');
const entreprise = document.getElementById('entreprise');
const FormeEntreprise = document.getElementById('formEntreprise');
const FormeEtudiant = document.getElementById('formEtudiant');
// Écouteur sur le bouton radio "Étudiant"
etudiant.addEventListener('change', () => {
  if (etudiant.checked) {
    FormeEntreprise.style.display = 'none'; // Cacher le formulaire entreprise
    FormeEtudiant.style.display = 'block';
  }
});

// Écouteur sur le bouton radio "Entreprise"
entreprise.addEventListener('change', () => {
  if (entreprise.checked) {
    FormeEntreprise.style.display = 'block'; // Afficher le formulaire entreprise
    FormeEtudiant.style.display = 'none';
  }
});






