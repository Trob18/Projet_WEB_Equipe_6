<?php

namespace app\Test;

use PHPUnit\Framework\TestCase;
use app\Model\Offer;
use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/OfferModel.php';

class OfferTest extends TestCase {
    private $offer;
    private $pdo;

    // Initialise l'environnement de test avant chaque test
    protected function setUp(): void {
        $this->pdo = (new \app\Config\ConfigDatabase())->connect();
        $this->offer = new Offer($this->pdo);
        $this->cleanUpTestOffers();
    }

    // Nettoie l'environnement après chaque test
    protected function tearDown(): void {
        $this->cleanUpTestOffers();
    }

    // Teste la création d'une offre dans la base de données
    public function testStoreOffer() {
        $data = $this->getTestOfferData();
        $id = $this->offer->StoreOffer($data);

        $this->assertGreaterThan(0, $id, "L'ID de l'offre insérée doit être supérieur à 0.");
        $offer = $this->fetchOfferById($id);

        $this->assertNotEmpty($offer, "L'offre insérée doit exister dans la base.");
        $this->assertEquals($data['TitleOffer'], $offer['Title_Offer'], "Le titre de l'offre doit correspondre.");
    }

    // Teste la récupération d'une offre spécifique
    public function testGetOffer() {
        $id = $this->insertTestOffer();
        $offer = $this->offer->GetOffer($id);

        $this->assertNotEmpty($offer, "L'offre récupérée ne doit pas être vide.");
        $this->assertEquals('Offre de test', $offer['Title_Offer'], "Le titre de l'offre récupérée doit être correct.");
    }

    // Teste la suppression d'une offre spécifique
    public function testRemoveOffer() {
        $id = $this->insertTestOffer();
        $result = $this->offer->RemoveOffer($id);

        $this->assertEquals(1, $result, "La suppression de l'offre doit retourner 1.");
        $offer = $this->fetchOfferById($id);

        $this->assertFalse($offer, "L'offre supprimée ne doit plus exister dans la base.");
    }

    // Teste la modification d'une offre existante
    public function testEditOffer() {
        $id = $this->insertTestOffer();
        $updatedData = [
            'TitleOffer' => 'Offre mise à jour',
            'SkillsOffer' => 'PHP, Symfony',
            'AddressOffer' => 'Lyon',
            'DateOffer' => '2025-04-01',
            'ActivitySectorOffer' => 'Développement',
            'SalaryOffer' => 50000,
            'DescriptionOffer' => 'Offre mise à jour pour test.'
        ];

        $result = $this->offer->EditOffer($id, $updatedData);

        $this->assertEquals(1, $result, "La mise à jour doit retourner 1.");
        $offer = $this->fetchOfferById($id);

        $this->assertEquals('Offre mise à jour', $offer['Title_Offer'], "Le titre de l'offre mise à jour doit être correct.");
        $this->assertEquals('PHP, Symfony', $offer['Skills_Offer'], "Les compétences de l'offre mise à jour doivent être correctes.");
    }

    // Teste la récupération de toutes les offres
    public function testGetAllOffers() {
        $this->insertTestOffer('Offre 1');
        $this->insertTestOffer('Offre 2');
        $offers = $this->offer->GetAllOffer();

        $this->assertNotEmpty($offers, "La liste des offres ne doit pas être vide.");
        $titles = array_column($offers, 'Title_Offer');

        $this->assertContains('Offre 1', $titles, "La liste des offres doit contenir 'Offre 1'.");
        $this->assertContains('Offre 2', $titles, "La liste des offres doit contenir 'Offre 2'.");
    }

    // Crée des données de test pour une offre
    private function getTestOfferData($title = 'Offre de test') {
        return [
            'TitleOffer' => $title,
            'SkillsOffer' => 'PHP, MySQL',
            'AddressOffer' => 'Paris',
            'DateOffer' => '2025-03-28',
            'ActivitySectorOffer' => 'Informatique',
            'SalaryOffer' => 45000,
            'DescriptionOffer' => 'Ceci est une offre de test.'
        ];
    }

    // Insère une offre de test dans la base de données
    private function insertTestOffer($title = 'Offre de test') {
        $data = $this->getTestOfferData($title);
        $stmt = $this->pdo->prepare("
            INSERT INTO offers (Title_Offer, Skills_Offer, Address_Offer, Date_Offer, ActivitySector_Offer, Salary_Offer, Description_Offer)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }

    // Récupère une offre par son ID
    private function fetchOfferById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM offers WHERE id_Offer = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nettoie les offres de test de la base de données
    private function cleanUpTestOffers() {
        $this->pdo->exec("DELETE FROM offers WHERE Title_Offer LIKE 'Offre%'");
    }
}
