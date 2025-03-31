<?php

namespace app\Test;

use PHPUnit\Framework\TestCase;
use app\Model\OfferModel;
use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/OfferModel.php';

class OfferTest extends TestCase {
    private $offerModel;
    private $pdo;

    // Initialise l'environnement de test avant chaque test
    protected function setUp(): void {
        $this->pdo = (new \app\Config\ConfigDatabase())->connect();
        $this->offerModel = new OfferModel($this->pdo);
        $this->cleanUpTestOffers();
    }

    // Nettoie l'environnement après chaque test
    protected function tearDown(): void {
        $this->cleanUpTestOffers();
    }

    // Teste la création d'une offre dans la base de données
    public function testStoreOffer() {
        $data = $this->getTestOfferData();
        $id = $this->offerModel->storeOffer($data);

        $this->assertGreaterThan(0, $id, "L'ID de l'offre insérée doit être supérieur à 0.");
        $offer = $this->fetchOfferById($id);

        $this->assertNotEmpty($offer, "L'offre insérée doit exister dans la base.");
        $this->assertEquals($data['Title_Offer'], $offer['Title_Offer'], "Le titre de l'offre doit correspondre.");
    }

    // Teste la récupération d'une offre spécifique
    public function testGetOffer() {
        $id = $this->insertTestOffer();
        $offer = $this->offerModel->getOffer('Id_Offer', $id);

        $this->assertNotEmpty($offer, "L'offre récupérée ne doit pas être vide.");
        $this->assertEquals('Offre de test', $offer['Title_Offer'], "Le titre de l'offre récupérée doit être correct.");
    }

    // Teste la suppression d'une offre spécifique
    public function testRemoveOffer() {
        $id = $this->insertTestOffer();
        $result = $this->offerModel->removeOffer($id);

        $this->assertTrue($result, "La suppression de l'offre doit retourner true.");
        $offer = $this->fetchOfferById($id);

        $this->assertFalse($offer, "L'offre supprimée ne doit plus exister dans la base.");
    }

    // Teste la modification d'une offre existante
    public function testEditOffer() {
        $id = $this->insertTestOffer();
        $updatedData = [
            'Title_Offer' => 'Offre mise à jour',
            'Skills_Offer' => 'PHP, Symfony',
            'Address_Offer' => 'Lyon',
            'Date_Offer' => '2025-04-01',
            'ActivitySector_Offer' => 'Développement',
            'Salary_Offer' => 50000,
            'Description_Offer' => 'Offre mise à jour pour test.'
        ];

        $result = $this->offerModel->editOffer($id, $updatedData);

        $this->assertTrue($result, "La mise à jour doit retourner true.");
        $offer = $this->fetchOfferById($id);

        $this->assertEquals('Offre mise à jour', $offer['Title_Offer'], "Le titre de l'offre mise à jour doit être correct.");
        $this->assertEquals('PHP, Symfony', $offer['Skills_Offer'], "Les compétences de l'offre mise à jour doivent être correctes.");
    }

    // Teste la récupération de toutes les offres
    public function testGetAllOffers() {
        $this->insertTestOffer('Offre 1');
        $this->insertTestOffer('Offre 2');
        $offers = $this->offerModel->getAllOffers();

        $this->assertNotEmpty($offers, "La liste des offres ne doit pas être vide.");
        $titles = array_column($offers, 'Title_Offer');

        $this->assertContains('Offre 1', $titles, "La liste des offres doit contenir 'Offre 1'.");
        $this->assertContains('Offre 2', $titles, "La liste des offres doit contenir 'Offre 2'.");
    }

    // Crée des données de test pour une offre
    private function getTestOfferData($title = 'Offre de test') {
        return [
            'Title_Offer' => $title,
            'Skills_Offer' => 'PHP, MySQL',
            'Address_Offer' => 'Paris',
            'Date_Offer' => '2025-03-28',
            'ActivitySector_Offer' => 'Informatique',
            'Salary_Offer' => 45000,
            'Description_Offer' => 'Ceci est une offre de test.'
        ];
    }

    // Insère une offre de test dans la base de données
    private function insertTestOffer($title = 'Offre de test') {
        $data = $this->getTestOfferData($title);
        $stmt = $this->pdo->prepare("
            INSERT INTO offers (Title_Offer, Skills_Offer, Address_Offer, Date_Offer, ActivitySector_Offer, Salary_Offer, Description_Offer)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['Title_Offer'],
            $data['Skills_Offer'],
            $data['Address_Offer'],
            $data['Date_Offer'],
            $data['ActivitySector_Offer'],
            $data['Salary_Offer'],
            $data['Description_Offer']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Récupère une offre par son ID
    private function fetchOfferById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM offers WHERE Id_Offer = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nettoie les offres de test de la base de données
    private function cleanUpTestOffers() {
        $this->pdo->exec("DELETE FROM offers WHERE Title_Offer LIKE 'Offre%'");
    }
}
