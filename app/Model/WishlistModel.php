<?php

namespace app\Model;

use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';

class WishlistModel {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addToWishlist($userId, $offerId)
    {
        $stmt = $pdo->prepare("INSERT IGNORE INTO add_to_wishlist (Id_Account, Id_Offer) VALUES (?, ?)");
        return $stmt->execute([$userId, $offerId]);
    }


    public function removeFromWishlist($userId, $offerId)
    {
        $stmt = $pdo->prepare("DELETE FROM add_to_wishlist WHERE Id_Account = ? AND Id_Offer = ?");
        return $stmt->execute([$userId, $offerId]);
    }

    public function isInWishlist($userId, $offerId)
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM add_to_wishlist WHERE Id_Account = ? AND Id_Offer = ?");
        $stmt->execute([$userId, $offerId]);
        return $stmt->fetchColumn() > 0;
    }


    public function getUserWishlist($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT Id_Offer
            FROM add_to_wishlist
            WHERE Id_Account = :userId;
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }











}