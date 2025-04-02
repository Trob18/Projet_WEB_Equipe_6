<?php

namespace app\Model;

use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';

class WishlistModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addToWishlist($pdo, $userId, $offerId)
    {
        $stmt = $pdo->prepare("INSERT IGNORE INTO addToWishlist (Id_Account, Id_offer) VALUES (?, ?)");
        return $stmt->execute([$userId, $offerId]);
    }


    public function removeFromWishlist($pdo, $userId, $offerId)
    {
        $stmt = $pdo->prepare("DELETE FROM addToWishlist WHERE Id_Account = ? AND Id_offer = ?");
        return $stmt->execute([$userId, $offerId]);
    }

    public function isInWishlist($pdo, $userId, $offerId)
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM addToWishlist WHERE Id_Account = ? AND Id_offer = ?");
        $stmt->execute([$userId, $offerId]);
        return $stmt->fetchColumn() > 0;
    }


    public function getUserWishlist($pdo, $userId)
    {
        $stmt = $pdo->prepare("
            SELECT o.* 
            FROM offers o
            JOIN addToWishlist w ON o.id = w.Id_offer
            WHERE w.Id_Account = ?
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}   
