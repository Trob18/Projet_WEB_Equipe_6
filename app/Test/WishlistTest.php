<?php

namespace app\Test;

use app\Model\WishlistModel;
use PHPUnit\Framework\TestCase;


require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\config\ConfigDatabase2.php';
require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\app\Model\AccountModel.php';

class WishlistTest extends TestCase
{
    private $pdo;
    private $wishlist;

    protected function setUp(): void
    {
        // Configure la connexion à la base de données (change les paramètres si nécessaire)
        $configDatabase = new \app\config\ConfigDatabase2nd();
        $this->pdo = $configDatabase->getConnection();
        $this->wishlist = new WishlistModel($this->pdo);

        // Nettoyer la table 'add_to_wishlist' avant chaque test
        $this->pdo->exec("DELETE FROM add_to_wishlist");
    }

    // Test de la méthode addToWishlist
    public function testAddToWishlist()
    {
        $userId = 1;
        $offerId = 10;

        // Ajoute l'offre à la liste de souhaits
        $result = $this->wishlist->addToWishlist($userId, $offerId);
        $this->assertTrue($result);

        // Vérifie que l'offre a bien été ajoutée à la liste de souhaits
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM add_to_wishlist WHERE Id_Account = ? AND Id_Offer = ?");
        $stmt->execute([$userId, $offerId]);
        $count = $stmt->fetchColumn();

        $this->assertEquals(1, $count, "L'offre devrait être ajoutée à la liste de souhaits.");
    }

    // Test de la méthode removeFromWishlist
    public function testRemoveFromWishlist()
    {
        $userId = 1;
        $offerId = 10;

        // Ajouter l'offre avant de la supprimer
        $this->wishlist->addToWishlist($userId, $offerId);

        // Supprimer l'offre de la liste de souhaits
        $result = $this->wishlist->removeFromWishlist($userId, $offerId);
        $this->assertTrue($result);

        // Vérifie que l'offre a bien été supprimée
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM add_to_wishlist WHERE Id_Account = ? AND Id_Offer = ?");
        $stmt->execute([$userId, $offerId]);
        $count = $stmt->fetchColumn();

        $this->assertEquals(0, $count, "L'offre devrait avoir été supprimée de la liste de souhaits.");
    }

    // Test de la méthode isInWishlist
    public function testIsInWishlist()
    {
        $userId = 1;
        $offerId = 10;

        // Ajouter l'offre avant de vérifier
        $this->wishlist->addToWishlist($userId, $offerId);

        // Vérifie si l'offre est dans la liste de souhaits
        $result = $this->wishlist->isInWishlist($userId, $offerId);
        $this->assertTrue($result, "L'offre devrait être présente dans la liste de souhaits.");

        // Vérifie pour une offre qui n'est pas dans la liste
        $result = $this->wishlist->isInWishlist($userId, 99);  // Offre inexistante
        $this->assertFalse($result, "L'offre avec l'ID 99 ne devrait pas être dans la liste de souhaits.");
    }

    // Test de la méthode getUserWishlist
    public function testGetUserWishlist()
    {
        $userId = 1;

        // Ajouter des offres à la liste de souhaits
        $this->wishlist->addToWishlist($userId, 10);
        $this->wishlist->addToWishlist($userId, 20);

        // Récupère la liste des offres de l'utilisateur
        $wishlist = $this->wishlist->getUserWishlist($userId);
        $this->assertIsArray($wishlist, "La liste des souhaits devrait être un tableau.");
        $this->assertCount(2, $wishlist, "L'utilisateur devrait avoir 2 offres dans sa liste de souhaits.");
        $this->assertContains(10, $wishlist, "L'ID de l'offre 10 devrait être dans la liste de souhaits.");
        $this->assertContains(20, $wishlist, "L'ID de l'offre 20 devrait être dans la liste de souhaits.");
    }
}