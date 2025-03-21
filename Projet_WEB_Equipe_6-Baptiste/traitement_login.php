<?php
session_start();
require_once 'connexionBDD.php';

// Initialisation des variables
$erreur = "";
$redirect = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (empty($email) || empty($password )) {
        $erreur = "Veuillez remplir tous les champs";
    } else {
        // Recherche de l'utilisateur dans la base de données
        $requete = $connexion->prepare("SELECT * FROM comptes WHERE Courriel_Compte = :email");
        $requete->bindParam(':email', $email);
        $requete->execute();
        
        if ($requete->rowCount() > 0) {
            $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
            
            // Vérification du mot de passe
            if ($password == $utilisateur['Mot_de_passe_Compte']) {
                // Connexion réussie
                $_SESSION['utilisateur_id'] = $utilisateur['ID_Compte'];
                $_SESSION['utilisateur_email'] = $utilisateur['Courriel_Compte'];
                
                // Redirection vers la page d'accueil après connexion
                $redirect = true;
            } else {
                $erreur = "Mot de passe incorrect";
            }
        } else {
            $erreur = "Cet email n'existe pas dans notre base de données";
        }
    }
}

// Retourner une réponse
if ($redirect) {
    // Rediriger vers la page d'accueil
    header("Location: Accueil.html");
    exit();
} else {
    // Rediriger vers la page de connexion avec un message d'erreur
    $_SESSION['erreur_login'] = $erreur;
    header("Location: index.html");
    exit();
}

?>
