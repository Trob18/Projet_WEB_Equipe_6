<?php

namespace app\Controller;

require_once __DIR__ . '/../Model/WishlistModel.php';
use app\Model\WishlistModel;
use PDO;

class WishlistController {
    private $wishlistModel;
    
    public function __construct(PDO $pdo) {
        $this->wishlistModel = new WishlistModel($pdo);
    }

    public function getWishlist($column, $value, $selectColumn = '*'){
        $Wishlist = $this->wishlistModel->getWishlist($column, $value, $selectColumn = '*');
        return $Wishlist ? $Wishlist : "Compte introuvable!";
    }

    public function addToWishlist($userId, $offerId)
    {
        return $this->wishlistModel->addToWishlist($userId, $offerId);
    }

    public function removeFromWishlist($userId, $offerId)
    {
        return $this->wishlistModel->removeFromWishlist($userId, $offerId);
    }

    public function isInWishlist($userId, $offerId)
    {
        return $this->wishlistModel->isInWishlist($userId, $offerId);
    }

    public function getUserWishlist($userId)
    {
        return $this->wishlistModel->getUserWishlist($userId);
    }
}