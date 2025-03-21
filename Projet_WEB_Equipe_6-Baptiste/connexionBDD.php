<?php
// Paramètres de connexion à la base de données
$serveur = "localhost";  // Généralement localhost en développement local
$utilisateur = "root";  // Remplacez par votre nom d'utilisateur MySQL
$motdepasse = "";  // Remplacez par votre mot de passe MySQL
$basededonnees = "projet_web";  // Remplacez par le nom de votre base de données

// Créer la connexion
try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$basededonnees", $utilisateur, $motdepasse);
    // Configurer PDO pour signaler les erreurs
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie"; // Vous pouvez décommenter pour tester
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die(); // Arrête l'exécution du script en cas d'erreur
}
?>
