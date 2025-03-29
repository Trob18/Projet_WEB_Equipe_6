<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/NotesModel.php';

use app\Model\Notes;

/**
 * Contrôleur pour la gestion des notes
 */
class NotesController {
    private $notesModel;

    /**
     * Constructeur du contrôleur des notes
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo) {
        $this->notesModel = new Notes($pdo);
    }

    /**
     * Récupère une note spécifique par son identifiant
     * 
     * @param int $id Identifiant de la note
     * @return array|string Données de la note ou message d'erreur
     */
    public function GetNotes($id) {
        $notes = $this->notesModel->GetNotes($id);
        if (!$notes) {
            return "La note n'existe pas !";
        } else {
            return $notes;
        }
    }

    /**
     * Récupère toutes les notes
     * 
     * @return array|string Liste des notes ou message d'erreur
     */
    public function GetAllNotes() {
        $notes = $this->notesModel->GetAllNotes();
        if (!$notes) {
            return "Aucune Note Trouvée !";
        } else {
            return $notes;
        }
    }
    
    /**
     * Crée une nouvelle note
     * 
     * @param array $newdata Données de la nouvelle note
     * @return string Message de succès ou d'erreur
     */
    public function createNotes($newdata) {
        $verif = ['Note', 'Comment'];
        foreach ($verif as $index) {
            if (empty($newdata[$index])) {
                return "Contenu non complété !";
            }
        }
        $notes = $this->notesModel->StoreNotes($newdata);
        if (!$notes) {
            return "Échec de la création";
        } else {
            return "Note Créée !";
        }
    }

    /**
     * Supprime une note spécifique
     * 
     * @param int $id Identifiant de la note à supprimer
     * @return string Message de succès ou d'erreur
     */
    public function RemoveNotes($id) {
        $notes = $this->notesModel->RemoveNotes($id);
        if (!$notes) {
            return "Note Introuvable";
        } else {
            return "Note Supprimée";
        }
    }

    /**
     * Supprime toutes les notes
     * 
     * @return string Message de succès ou d'erreur
     */
    public function RemoveAllNotes() {
        $notes = $this->notesModel->RemoveAllNotes();
        if (!$notes) {
            return "Note(s) Introuvable(s)";
        } else {
            return "Toutes les notes sont supprimées";
        }
    }

    /**
     * Modifie une note existante
     * 
     * @param int $id Identifiant de la note à modifier
     * @param array $newdata Nouvelles données de la note
     * @return string Message de succès ou d'erreur
     */
    public function EditNotes($id, $newdata) {
        $verif = ['Note', 'Comment'];
        foreach ($verif as $index) {
            if (empty($newdata[$index])) {
                return "Contenu non complété !";
            }
        }
        $notes = $this->notesModel->EditNotes($id, $newdata);
        if (!$notes) {
            return "Échec de la Modification";
        } else {
            return "Modification Réussie !";
        }
    }
}
?>
