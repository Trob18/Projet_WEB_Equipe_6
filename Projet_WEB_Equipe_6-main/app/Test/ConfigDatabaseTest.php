<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert; 
require_once __DIR__ . '/../../config/ConfigDatabase2.php'; 

class ConfigDatabaseTest extends TestCase
{
    // Vérifie que la connexion fonctionne et est une instance de PDO
    public function testConnection()
    {
        $configDatabase = new \app\config\ConfigDatabase2nd();
        $pdo = $configDatabase->getConnection();

        Assert::assertInstanceOf(PDO::class, $pdo, "La connexion doit retourner une instance de PDO.");
    }

    // Vérifie que l'attribut ERRMODE est bien défini
    public function testErrMode()
    {
        $configDatabase = new \app\config\ConfigDatabase2nd();
        $pdo = $configDatabase->getConnection();
        Assert::assertEquals(PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(PDO::ATTR_ERRMODE), 
            "L'attribut PDO::ATTR_ERRMODE doit être défini sur PDO::ERRMODE_EXCEPTION.");
    }
}
