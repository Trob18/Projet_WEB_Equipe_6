<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert; 
require_once __DIR__ . '/../../config/ConfigDatabase.php'; 

class ConfigDatabaseTest extends TestCase
{
    // Vérifie que la connexion fonctionne et est une instance de PDO
    public function testConnection()
    {
        $pdo = require __DIR__ . '/../../config/ConfigDatabase.php';
        Assert::assertInstanceOf(PDO::class, $pdo, "La connexion doit retourner une instance de PDO.");
    }

    // Vérifie que l'attribut ERRMODE est bien défini
    public function testErrMode()
    {
        $pdo = require __DIR__ . '/../../config/ConfigDatabase.php';
        Assert::assertEquals(PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(PDO::ATTR_ERRMODE), 
            "L'attribut PDO::ATTR_ERRMODE doit être défini sur PDO::ERRMODE_EXCEPTION.");
    }
}
