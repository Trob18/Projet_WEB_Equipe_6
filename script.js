// Gestion de la visibilité des blocs
document.getElementById('mon-bouton').addEventListener('click', rendreInvisible);

function rendreInvisible() {
  const bloc = document.getElementById('bloc-a-cacher');
  if (bloc) {
    bloc.style.display = 'none';
  } else {
    console.error("L'élément avec l'ID 'bloc-a-cacher' n'existe pas !");
  }
  
  const bloc2 = document.getElementById('bloc-a-cacher2');
  if (bloc2) {
    bloc2.style.display = 'none';
  } else {
    console.error("L'élément avec l'ID 'bloc-a-cacher2' n'existe pas !");
  }
  
  const bloc3 = document.getElementById('bloc-a-cacher3');
  if (bloc3) {
    bloc3.style.display = 'none';
  } else {
    console.error("L'élément avec l'ID 'bloc-a-cacher3' n'existe pas !");
  }
  
  const bloc4 = document.getElementById("bloc-a-afficher");
  if(bloc4) {
    bloc4.style.display = 'block';
  } else {
    console.error("L'élément avec l'ID 'bloc-a-afficher' n'existe pas !");
  }
}

// Ajout des écouteurs d'événements pour le formulaire et le bouton d'annulation
document.getElementById('bouton-annuler').addEventListener('click', rendreVisible);

function rendreVisible(event) {
  // Empêcher le rechargement de la page lors du submit
  if (event) {
    event.preventDefault();
  }
  
  // Réinitialiser le formulaire
  document.getElementById('my-form').reset();
  
  // Réinitialiser les badges
  resetBadges();
  
  // Afficher les blocs
  const bloc1 = document.getElementById('bloc-a-cacher');
  if (bloc1) bloc1.style.display = 'block';
  
  const bloc2 = document.getElementById('bloc-a-cacher2');
  if (bloc2) bloc2.style.display = 'block';
  
  const bloc3 = document.getElementById('bloc-a-cacher3');
  if (bloc3) bloc3.style.display = 'block';

  // Masquer le bloc
  const bloc4 = document.getElementById('bloc-a-afficher');
  if (bloc4) bloc4.style.display = 'none';
  
  // Effacer les messages d'erreur
  errorMessage.textContent = '';
  
  // Supprimer les classes d'erreur
  document.querySelectorAll('.error-field').forEach(field => {
    field.classList.remove('error-field');
  });
}

document.getElementById('bouton-reset').addEventListener('click', renitialisation);

function renitialisation(event){
  if (event) {
    event.preventDefault();
  }
  document.getElementById('my-form').reset();
  resetBadges();
  
  // Effacer les messages d'erreur
  errorMessage.textContent = '';
  
  // Supprimer les classes d'erreur
  document.querySelectorAll('.error-field').forEach(field => {
    field.classList.remove('error-field');
  });
}

//-------------------------------------------------------------------------------------------------------------
// Gestion des badges

// Limite de badges sélectionnables
const MAX_BADGES = 4;

// Récupérer les éléments nécessaires
const badgeList = document.getElementById('badge-list');
const badgeInput = document.getElementById('badge-input');
const addBadgeBtn = document.getElementById('add-badge-btn');
const errorMessage = document.getElementById('error-message');
const hiddenInput = document.getElementById('selected-badges');
const form = document.getElementById('my-form');
const boutonAnnuler = document.getElementById('bouton-annuler');

// Sauvegarder l'état initial des badges
const initialBadgeListHTML = badgeList.innerHTML;

// Fonction pour réinitialiser les badges
function resetBadges() {
  // Réinitialiser le contenu de la liste de badges
  badgeList.innerHTML = initialBadgeListHTML;
  
  // Réinitialiser les badges sélectionnés
  const selectedBadges = document.querySelectorAll('.badge.selected');
  selectedBadges.forEach(badge => {
    badge.classList.remove('selected');
  });
  
  // Réinitialiser le champ caché
  if (hiddenInput) hiddenInput.value = '';
  
  // Effacer le message d'erreur
  if (errorMessage) errorMessage.textContent = '';
  
  // Vider le champ de saisie
  if (badgeInput) badgeInput.value = '';
}

// Fonction pour gérer la sélection/désélection des badges
function toggleBadgeSelection(badge) {
  const isSelected = badge.classList.contains('selected');

  if (isSelected) {
    badge.classList.remove('selected');
  } else {
    const selectedBadges = document.querySelectorAll('.badge.selected');
    if (selectedBadges.length >= MAX_BADGES) {
      errorMessage.textContent = `Vous ne pouvez sélectionner que ${MAX_BADGES} badges.`;
      setTimeout(() => (errorMessage.textContent = ''), 3000);
      return;
    }
    badge.classList.add('selected');
  }

  updateHiddenInput();
}

// Fonction pour mettre à jour le champ caché
function updateHiddenInput() {
  const selectedBadges = Array.from(document.querySelectorAll('.badge.selected')).map(
    (badge) => badge.textContent.trim()
  );
  hiddenInput.value = selectedBadges.join(',');
}

// Ajouter un événement de clic sur les badges existants et futurs (délégation d'événements)
badgeList.addEventListener('click', (event) => {
  if (event.target.classList.contains('badge')) {
    toggleBadgeSelection(event.target);
  }
});

// Fonction pour ajouter un nouveau badge
function addNewBadge() {
  const newBadgeName = badgeInput.value.trim();

  if (newBadgeName === '') {
    errorMessage.textContent = 'Veuillez entrer un nom de badge.';
    setTimeout(() => (errorMessage.textContent = ''), 3000);
    return;
  }

  // Vérifier si le badge existe déjà
  const existingBadge = Array.from(badgeList.children).find(
    (badge) => badge.textContent.trim().toLowerCase() === newBadgeName.toLowerCase()
  );
  
  if (existingBadge) {
    errorMessage.textContent = 'Ce badge existe déjà.';
    setTimeout(() => (errorMessage.textContent = ''), 3000);
    return;
  }

  // Créer et ajouter le nouveau badge
  const newBadge = document.createElement('div');
  newBadge.classList.add('badge');
  newBadge.textContent = newBadgeName;
  badgeList.appendChild(newBadge);
  
  // Vider le champ de saisie
  badgeInput.value = '';
}

// Ajouter un événement au bouton "Ajouter"
addBadgeBtn.addEventListener('click', addNewBadge);

// Ajouter un événement "Enter" dans le champ de saisie
badgeInput.addEventListener('keypress', (event) => {
  if (event.key === 'Enter') {
    event.preventDefault();
    addNewBadge();
  }
});

// Fonction pour mettre en évidence les champs en erreur
function highlightErrorField(fieldId) {
  const field = document.getElementById(fieldId);
  if (field) {
    field.classList.add('error-field');
    
    field.addEventListener('input', function() {
      this.classList.remove('error-field');
    }, { once: true });
  }
}

// Fonction pour mettre en évidence la section des badges en erreur
function highlightBadgeSection() {
  const badgeSection = document.getElementById('badge-list');
  if (badgeSection) {
    badgeSection.classList.add('error-field');
    
    badgeSection.addEventListener('click', function() {
      this.classList.remove('error-field');
    }, { once: true });
  }
}

//------------------------------------------------------------------

function creerNouvelleOffre(event) {
  event.preventDefault();
  
  // Récupération des valeurs du formulaire
  const titreOffre = document.getElementById('nom-offre').value.trim();
  const adresseOffre = document.getElementById('adresse-offre').value.trim();
  const typeContrat = document.getElementById('contrat').value;
  const description = document.getElementById('description').value.trim();
  const salaireMin = document.getElementById('numberInput1').value;
  const salaireMax = document.getElementById('numberInput2').value;
  
  // Récupération des badges sélectionnés
  const selectedBadges = document.querySelectorAll('.badge.selected');
  
  // Validation des champs obligatoires
  if (!titreOffre) {
    errorMessage.textContent = "Le nom de l'offre est obligatoire";
    highlightErrorField('nom-offre');
    document.getElementById('nom-offre').focus();
    return;
  }
  
  if (!adresseOffre) {
    errorMessage.textContent = "L'adresse de l'offre est obligatoire";
    highlightErrorField('adresse-offre');
    document.getElementById('adresse-offre').focus();
    return;
  }
  
  if (!typeContrat || typeContrat === "") {
    errorMessage.textContent = "Veuillez sélectionner un type de contrat";
    highlightErrorField('contrat');
    document.getElementById('contrat').focus();
    return;
  }
  
  if (selectedBadges.length === 0) {
    errorMessage.textContent = "Veuillez sélectionner au moins un secteur d'activité";
    highlightBadgeSection();
    return;
  }
  
  if (!description) {
    errorMessage.textContent = "La description du poste est obligatoire";
    highlightErrorField('description');
    document.getElementById('description').focus();
    return;
  }
  
  // Si toutes les validations sont passées, continuer avec la création de l'offre
  const secteurs = Array.from(selectedBadges).map(badge => badge.textContent.trim());
  
  // Création de l'élément d'offre
  const nouvelleOffre = document.createElement('div');
  nouvelleOffre.className = 'offre';
  
  // Création du titre
  const titre = document.createElement('h3');
  titre.textContent = titreOffre;
  nouvelleOffre.appendChild(titre);
  
  // Création de l'adresse
  const adresse = document.createElement('p');
  adresse.textContent = adresseOffre;
  nouvelleOffre.appendChild(adresse);
  
  // Ajout du type de contrat
  const tagContrat = document.createElement('span');
  tagContrat.className = 'tag';
  tagContrat.textContent = typeContrat;
  nouvelleOffre.appendChild(tagContrat);
  
  // Ajout des secteurs d'activité
  secteurs.forEach(secteur => {
    const tagSecteur = document.createElement('span');
    tagSecteur.className = 'tag';
    tagSecteur.textContent = secteur;
    nouvelleOffre.appendChild(tagSecteur);
  });
  
  // Ajout du salaire si spécifié
  if (salaireMin && salaireMax) {
    const salaire = document.createElement('span');
    salaire.className = 'salaire';
    salaire.textContent = `${salaireMin} - ${salaireMax} €`;
    nouvelleOffre.appendChild(salaire);
  }
  
  // Ajout de la description
  const desc = document.createElement('p');
  desc.textContent = description;
  nouvelleOffre.appendChild(desc);
  
  // Ajout du bouton "Voir plus"
  const bouton = document.createElement('button');
  bouton.className = 'voir-plus';
  bouton.textContent = 'Voir plus';
  nouvelleOffre.appendChild(bouton);
  
  // Ajout de l'offre à la liste des offres
  const conteneurOffres = document.querySelector('.offres');
  conteneurOffres.prepend(nouvelleOffre); // Ajoute au début pour mettre en évidence la nouvelle offre
  
  // Réinitialisation du formulaire et affichage des offres
  rendreVisible();
}

// Remplacer l'événement submit existant par celui-ci
document.getElementById('my-form').addEventListener('submit', creerNouvelleOffre);

// Ajouter le CSS pour les champs en erreur
const style = document.createElement('style');
style.textContent = `
  .error-field {
    border: 2px solid #d9534f !important;
    background-color: rgba(217, 83, 79, 0.05);
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  #error-message {
    animation: fadeIn 0.3s ease-in-out;
    color: #d9534f;
    font-weight: bold;
    margin-bottom: 10px;
    padding: 5px;
  }
`;
document.head.appendChild(style);
// Fonctionnalité de la barre de recherche
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons d'effacement
    const clearJobTitle = document.getElementById('clear-job-title');
    const clearLocation = document.getElementById('clear-location');
    const jobTitleInput = document.getElementById('job-title');
    const locationInput = document.getElementById('location');
    const searchButton = document.getElementById('search-button');
  
    // Effacer le champ de titre du poste
    if (clearJobTitle) {
      clearJobTitle.addEventListener('click', function() {
        jobTitleInput.value = '';
        jobTitleInput.focus();
      });
    }
  
    // Effacer le champ de localisation
    if (clearLocation) {
      clearLocation.addEventListener('click', function() {
        locationInput.value = '';
        locationInput.focus();
      });
    }
  
    // Afficher/masquer les boutons d'effacement selon que les champs sont vides ou non
    function toggleClearButton(input, button) {
      if (input && button) {
        input.addEventListener('input', function() {
          button.style.display = this.value ? 'block' : 'none';
        });
        // Initialiser l'état du bouton
        button.style.display = input.value ? 'block' : 'none';
      }
    }
  
    toggleClearButton(jobTitleInput, clearJobTitle);
    toggleClearButton(locationInput, clearLocation);
  
    // Fonction de recherche
    if (searchButton) {
      searchButton.addEventListener('click', function() {
        // Récupérer les valeurs des champs
        const jobTitle = jobTitleInput ? jobTitleInput.value : '';
        const location = locationInput ? locationInput.value : '';
        const radius = document.getElementById('radius') ? document.getElementById('radius').value : '';
        const contract = document.getElementById('contract') ? document.getElementById('contract').value : '';
        const sector = document.getElementById('sector') ? document.getElementById('sector').value : '';
        const salary = document.getElementById('salary') ? document.getElementById('salary').value : '';
        const company = document.getElementById('company') ? document.getElementById('company').value : '';
        const locationType = document.getElementById('location-type') ? document.getElementById('location-type').value : '';
        const education = document.getElementById('education') ? document.getElementById('education').value : '';
        
        // Construire l'objet de recherche
        const searchParams = {
          jobTitle,
          location,
          radius,
          contract,
          sector,
          salary,
          company,
          locationType,
          education
        };
        
        console.log('Recherche avec les paramètres:', searchParams);
        
        // Filtrer les offres existantes (exemple de fonctionnalité)
        filterOffers(searchParams);
      });
    }
  
    // Fonction pour filtrer les offres (exemple)
    // Fonction pour filtrer les offres (exemple)
function filterOffers(params) {
    const offres = document.querySelectorAll('.offre:not(#template)');
    let matchFound = false;
  
    offres.forEach(offre => {
      const titre = offre.querySelector('h3').textContent.toLowerCase();
      const description = offre.querySelector('p:last-of-type').textContent.toLowerCase();
      const tags = Array.from(offre.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase());
      
      // Vérifier si l'offre correspond aux critères
      let match = true;
      
      if (params.jobTitle && !titre.includes(params.jobTitle.toLowerCase())) {
        match = false;
      }
      
      if (params.location && !offre.querySelector('p').textContent.toLowerCase().includes(params.location.toLowerCase())) {
        match = false;
      }
      
      if (params.contract && !tags.includes(params.contract.toLowerCase())) {
        match = false;
      }
      
      if (params.sector && !tags.some(tag => tag.includes(params.sector.toLowerCase()))) {
        match = false;
      }
      
      // Afficher ou masquer l'offre selon qu'elle correspond ou non
      offre.style.display = match ? 'block' : 'none';
      
      if (match) {
        matchFound = true;
      }
    });
    
    // Gérer l'affichage du message "Aucune offre"
    let noResultsMessage = document.getElementById('no-results-message');
    
    if (!matchFound) {
      if (!noResultsMessage) {
        // Créer le message s'il n'existe pas
        noResultsMessage = document.createElement('div');
        noResultsMessage.id = 'no-results-message';
        noResultsMessage.className = 'no-results';
        noResultsMessage.textContent = 'Aucune offre ne correspond à votre recherche.';
        document.querySelector('.offres').appendChild(noResultsMessage);
      }
      noResultsMessage.style.display = 'block';
    } else if (noResultsMessage) {
      // Cacher le message si des offres correspondent
      noResultsMessage.style.display = 'none';
    }
  }
  
  });
  