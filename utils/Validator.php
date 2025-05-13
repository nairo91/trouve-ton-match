<?php
class Validator {
    private $errors = [];
    
    // Valider une chaîne non vide
    public function required($value, $field) {
        if (empty(trim($value))) {
            $this->errors[$field] = "Le champ $field est requis";
            return false;
        }
        return true;
    }
    
    // Valider un email
    public function email($value, $field) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "L'adresse email n'est pas valide";
            return false;
        }
        return true;
    }
    
    // Valider un nombre entier
    public function integer($value, $field) {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field] = "Le champ $field doit être un nombre entier";
            return false;
        }
        return true;
    }
    
    // Valider un minimum
    public function min($value, $field, $min) {
        if ($value < $min) {
            $this->errors[$field] = "Le champ $field doit être au moins $min";
            return false;
        }
        return true;
    }
    
    // Valider un maximum
    public function max($value, $field, $max) {
        if ($value > $max) {
            $this->errors[$field] = "Le champ $field doit être au plus $max";
            return false;
        }
        return true;
    }
    
    // Vérifier si des erreurs existent
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    // Récupérer toutes les erreurs
    public function getErrors() {
        return $this->errors;
    }
    
    // Récupérer l'erreur pour un champ spécifique
    public function getError($field) {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }
    
    // Nettoyer une valeur pour la base de données
    public static function sanitize($value) {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
}
?>
