<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/NotesModel.php';

use app\Model\NotesModel;
use PDO;

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
    public function __construct(PDO $pdo) {
        $this->notesModel = new NotesModel($pdo);
    }

    /**
     * Récupère une note spécifique par son identifiant
     * 
     * @param int $id Identifiant de la note
     * @return array|string Données de la note ou message d'erreur
     */
    public function getNote($id) {
        $note = $this->notesModel->getNote('Id_Notes', $id);
        return $note ? $note : "La note n'existe pas !";
    }

    /**
     * Récupère toutes les notes
     * 
     * @return array|string Liste des notes ou message d'erreur
     */
    public function getAllNotes() {
        $notes = $this->notesModel->getAllNotes();
        return !empty($notes) ? $notes : "Aucune Note Trouvée !";
    }
    
    /**
     * Crée une nouvelle note
     * 
     * @param array $newdata Données de la nouvelle note
     * @return string Message de succès ou d'erreur
     */
    public function createNote($newdata) {
        $requiredFields = ['Note', 'Comment'];
        
        foreach ($requiredFields as $field) {
            if (empty($newdata[$field])) {
                return "Le champ '$field' est requis!";
            }
        }
        
        $result = $this->notesModel->storeNote($newdata);
        return $result ? "Note Créée !" : "Échec de la création";
    }

    /**
     * Supprime une note spécifique
     * 
     * @param int $id Identifiant de la note à supprimer
     * @return string Message de succès ou d'erreur
     */
    public function removeNote($id) {
        // Vérifier si la note existe
        if (!$this->notesModel->getNote('Id_Notes', $id)) {
            return "Note Introuvable";
        }
        
        $result = $this->notesModel->removeNote($id);
        return $result ? "Note Supprimée" : "Échec de la suppression";
    }

    /**
     * Supprime toutes les notes
     * 
     * @return string Message de succès ou d'erreur
     */
    public function removeAllNotes() {
        $result = $this->notesModel->removeAllNotes();
        return $result ? "Toutes les notes sont supprimées" : "Note(s) Introuvable(s)";
    }

    /**
     * Modifie une note existante
     * 
     * @param int $id Identifiant de la note à modifier
     * @param array $newdata Nouvelles données de la note
     * @return string Message de succès ou d'erreur
     */
    public function editNote($id, $newdata) {
        // Vérifier si la note existe
        if (!$this->notesModel->getNote('Id_Notes', $id)) {
            return "Note Introuvable";
        }
        
        $requiredFields = ['Note', 'Comment'];
        
        foreach ($requiredFields as $field) {
            if (empty($newdata[$field])) {
                return "Le champ '$field' est requis!";
            }
        }
        
        $result = $this->notesModel->editNote($id, $newdata);
        return $result ? "Modification Réussie !" : "Échec de la Modification";
    }
}
?>
