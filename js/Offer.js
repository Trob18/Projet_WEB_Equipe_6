// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
  // Initialiser les fonctions et les écouteurs d'événements
  initializeEventListeners();


//-----------------Affichage Offre---------------------------------------

  const loadMoreBtn = document.getElementById('load-more-btn');
  const hiddenOffers = document.querySelectorAll('.hidden-offer');
  
  const offersPerLoad = 7; // Nombre d'offres à afficher à chaque clic
  let currentlyShown = 7; // Nombre d'offres actuellement affichées
  
  // Fonction pour afficher plus d'offres
  function loadMoreOffers() {
      let counter = 0;
      
      // Parcourir les offres cachées
      hiddenOffers.forEach(function(offer) {
          const offerIndex = parseInt(offer.dataset.index);
          
          // Afficher les prochaines offres (jusqu'à offersPerLoad)
          if (offerIndex > currentlyShown && offerIndex <= currentlyShown + offersPerLoad) {
              offer.classList.remove('hidden-offer');
              counter++;
          }
      });
      
      // Mettre à jour le compteur d'offres affichées
      currentlyShown += counter;
      
      // Masquer le bouton si toutes les offres sont affichées
      if (currentlyShown >= hiddenOffers.length + 7) {
          loadMoreBtn.style.display = 'none';
      }
  }
  
  // Ajouter l'événement de clic au bouton
  if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', loadMoreOffers);
  }



// --------------------Suppression / Affichage des blocs--------------------------------------



  
  function initializeEventListeners() {
    // Bouton pour rendre invisible
    const monBouton = document.getElementById('mon-bouton');
    if (monBouton) {
      monBouton.addEventListener('click', rendreInvisible);
    }
    
    // Bouton annuler
    const boutonAnnuler = document.getElementById('bouton-annuler');
    if (boutonAnnuler) {
      boutonAnnuler.addEventListener('click', rendreVisible);
    }
    
    // Bouton reset
    const boutonReset = document.getElementById('bouton-reset');
    if (boutonReset) {
      boutonReset.addEventListener('click', renitialisation);
    }
    
    // Gestion des badges
    initializeBadgeSystem();
    
    // Initialisation de la recherche
    initializeSearch();
  }
  
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
      bloc3.style.opacity = 0;
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

  function rendreVisible(event) {
    // Empêcher le rechargement de la page lors du submit
    if (event) {
      event.preventDefault();
    }
    
    // Réinitialiser le formulaire
    const myForm = document.getElementById('my-form');
    if (myForm) {
      myForm.reset();
    } 
    // Réinitialiser les badges
    resetBadges();
    
    // Afficher les blocs
    const bloc1 = document.getElementById('bloc-a-cacher');
    if (bloc1) bloc1.style.display = 'block';
    
    const bloc2 = document.getElementById('bloc-a-cacher2');
    if (bloc2) bloc2.style.display = 'block';
    
    const bloc3 = document.getElementById('bloc-a-cacher3');
    if (bloc3) bloc3.style.opacity = 1;

    // Masquer le bloc
    const bloc4 = document.getElementById('bloc-a-afficher');
    if (bloc4) bloc4.style.display = 'none';
    
    // Effacer les messages d'erreur
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
      errorMessage.textContent = '';
    }
    
    // Supprimer les classes d'erreur
    document.querySelectorAll('.error-field').forEach(field => {
      field.classList.remove('error-field');
    });
  }

  function renitialisation(event){
    if (event) {
      event.preventDefault();
    }
    
    const myForm = document.getElementById('my-form');
    if (myForm) {
      myForm.reset();
    }
    
    resetBadges();
    
    // Effacer les messages d'erreur
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
      errorMessage.textContent = '';
    }
    
    // Supprimer les classes d'erreur
    document.querySelectorAll('.error-field').forEach(field => {
      field.classList.remove('error-field');
    });
  }






//-------------------------Gestion des badges du secteur d'activité---------------------------------




  // Système de gestion des badges
  function initializeBadgeSystem() {
    // Limite de badges sélectionnables
    const MAX_BADGES = 1;

    // Récupérer les éléments nécessaires
    const badgeList = document.getElementById('badge-list');
    const badgeInput = document.getElementById('badge-input');
    const addBadgeBtn = document.getElementById('add-badge-btn');
    const errorMessage = document.getElementById('error-message');
    const hiddenInput = document.getElementById('selected-badges');
    
    // Vérifier si les éléments existent
    if (!badgeList) {
      console.error("L'élément 'badge-list' n'existe pas!");
      return;
    }
    
    // Sauvegarder l'état initial des badges
    const initialBadgeListHTML = badgeList.innerHTML;

    // Ajouter un événement de clic sur les badges existants et futurs (délégation d'événements)
    badgeList.addEventListener('click', (event) => {
      if (event.target.classList.contains('badge')) {
        toggleBadgeSelection(event.target);
      }
    });
    
    // Ajouter un événement au bouton "Ajouter" s'il existe
    if (addBadgeBtn) {
      addBadgeBtn.addEventListener('click', addNewBadge);
    }
    
    // Ajouter un événement "Enter" dans le champ de saisie s'il existe
    if (badgeInput) {
      badgeInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
          event.preventDefault();
          addNewBadge();
        }
      });
    }
    
    // Fonction pour réinitialiser les badges
    function resetBadges() {
      if (!badgeList) return;
      
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
      if (!badge) return;
      
      const isSelected = badge.classList.contains('selected');

      if (isSelected) {
        badge.classList.remove('selected');
      } else {
        const selectedBadges = document.querySelectorAll('.badge.selected');
        if (selectedBadges.length >= MAX_BADGES) {
          if (errorMessage) {
            errorMessage.textContent = `Vous ne pouvez sélectionner que ${MAX_BADGES} badges.`;
            setTimeout(() => {
              if (errorMessage) errorMessage.textContent = '';
            }, 3000);
          }
          return;
        }
        badge.classList.add('selected');
      }

      updateHiddenInput();
    }

    // Fonction pour mettre à jour le champ caché
    function updateHiddenInput() {
      if (!hiddenInput) return;
      
      const selectedBadges = Array.from(document.querySelectorAll('.badge.selected')).map(
        (badge) => badge.textContent.trim()
      );
      hiddenInput.value = selectedBadges.join(',');
    }

    // Exposer la fonction resetBadges globalement
    window.resetBadges = resetBadges;

    // SUPPRESSION DE LA FONCTION RÉCURSIVE QUI CAUSAIT L'ERREUR






//--------------------------------Ajout du système de recherche--------------------------------------------------






  }
  // Initialisation du système de recherche
  function initializeSearch() {
    // Gestion des boutons d'effacement
    const clearJobTitle = document.getElementById('clear-job-title');
    const clearLocation = document.getElementById('clear-location');
    const jobTitleInput = document.getElementById('job-title');
    const locationInput = document.getElementById('location');
    const searchButton = document.getElementById('search-button');
  
    // Effacer le champ de titre du poste
    if (clearJobTitle && jobTitleInput) {
      clearJobTitle.addEventListener('click', function() {
        jobTitleInput.value = '';
        jobTitleInput.focus();
      });
    }
  
    // Effacer le champ de localisation
    if (clearLocation && locationInput) {
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
        const contract = document.getElementById('contract') ? document.getElementById('contract').value : '';
        const sector = document.getElementById('sector') ? document.getElementById('sector').value : '';
        const salaryRange = document.getElementById('salaryfiltre') ? document.getElementById('salaryfiltre').value : '';
        const company = document.getElementById('companyfiltre') ? document.getElementById('companyfiltre').value : '';
        
        // Construire l'objet de recherche
        const searchParams = {
          jobTitle,
          contract,
          sector,
          salaryRange,
          company,
        };
        
        console.log('Recherche avec les paramètres:', searchParams);
        
        // Filtrer les offres existantes
        filterOffers(searchParams);
      });
    }
  
    // Fonction pour filtrer les offres
    function filterOffers(params) {
      const offres = document.querySelectorAll('.offre:not(#template)');
      let matchFound = false;
    
      // Récupérer le nom de l'entreprise sélectionnée si nécessaire
      let selectedCompanyName = '';
      if (params.company) {
        const companySelect = document.getElementById('companyfiltre');
        const selectedOption = companySelect.options[companySelect.selectedIndex];
        selectedCompanyName = selectedOption.textContent.trim().toLowerCase();
      }
    
      // Traiter la tranche de salaire
      let minSalary = 0;
      let maxSalary = Infinity;
      if (params.salaryRange) {
        const [min, max] = params.salaryRange.split('-').map(Number);
        minSalary = min;
        maxSalary = max;
      }
    
      offres.forEach(offre => {
        const titre = offre.querySelector('h3')?.textContent.toLowerCase() || '';
        const description = offre.querySelector('p:last-of-type')?.textContent.toLowerCase() || '';
        const tags = Array.from(offre.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase());
        const entreprise = offre.querySelector('.company-name')?.textContent.toLowerCase() || '';
        
        // Extraire le salaire de l'offre
        const salaireElement = offre.querySelector('.salaire');
        let salaire = 0;
        if (salaireElement) {
          // Extraire uniquement les chiffres du texte du salaire
          const salaireText = salaireElement.textContent;
          salaire = parseInt(salaireText.replace(/[^\d]/g, ''), 10) || 0;
        }
        
        // Vérifier si l'offre correspond aux critères
        let match = true;
        
        if (params.jobTitle && !titre.includes(params.jobTitle.toLowerCase())) {
          match = false;
        }
        
        if (params.location && !offre.querySelector('p')?.textContent.toLowerCase().includes(params.location.toLowerCase())) {
          match = false;
        }
        
        if (params.contract && !tags.includes(params.contract.toLowerCase())) {
          match = false;
        }
        
        if (params.sector && !tags.some(tag => tag.includes(params.sector.toLowerCase()))) {
          match = false;
        }
        
        // Utiliser le nom de l'entreprise au lieu de l'ID
        if (params.company && selectedCompanyName && entreprise !== selectedCompanyName) {
          match = false;
        }
        
        // Vérifier si le salaire est dans la tranche sélectionnée
        if (params.salaryRange && (salaire < minSalary || salaire > maxSalary)) {
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
      const offresContainer = document.querySelector('.offres');
      
      if (!matchFound && offresContainer) {
        if (!noResultsMessage) {
          // Créer le message s'il n'existe pas
          noResultsMessage = document.createElement('div');
          noResultsMessage.id = 'no-results-message';
          noResultsMessage.className = 'no-results';
          noResultsMessage.textContent = 'Aucune offre ne correspond à votre recherche.';
          offresContainer.appendChild(noResultsMessage);
        }
        noResultsMessage.style.display = 'block';
      } else if (noResultsMessage) {
        // Cacher le message si des offres correspondent
        noResultsMessage.style.display = 'none';
      }
    }
    
  }

      
    });