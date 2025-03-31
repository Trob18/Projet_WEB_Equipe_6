<?php

require_once __DIR__ . '/../Model/AccountModel.php';
$pdo = require __DIR__ . '/../../config/ConfigDatabase.php';

use app\Model\Account;

$account = new Account($pdo);

$lastName = 'Doe';
$firstName = 'John';
$email = 'john.doe@example.com';
$password = 'Password123!';

echo "🔑 Mot de passe avant hachage : " . $password . "\n";

$success = $account->storeAccount($lastName, $firstName, $email, $password);

if ($success) {
    echo "✅ Compte créé avec succès.\n";

    $storedAccount = $account->getAccount('Email_Account', $email);
    if ($storedAccount) {
        echo "📄 Compte récupéré :\n";
        print_r($storedAccount);


        echo "🔒 Mot de passe haché : " . $storedAccount['Password_Account'] . "\n";
        

        if (password_verify($password, $storedAccount['Password_Account'])) {
            echo "🔐 Mot de passe vérifié avec succès ✅\n";
        } else {
            echo "❌ Mot de passe incorrect.\n";
        }

        // Nettoyage
        $account->removeAccount($storedAccount['Id_Account']);
        echo "🧹 Compte supprimé après test.\n";
    }
} else {
    echo "❌ Échec de la création du compte.\n";
}
