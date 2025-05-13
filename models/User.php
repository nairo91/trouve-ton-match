<?php
require_once 'config/database.php';

class User {
    private $db;
    private $table = "joueurs";
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Vérifier les identifiants d'un utilisateur
    public function authenticate($pseudo, $mot_de_passe) {
        $query = "SELECT * FROM {$this->table} WHERE pseudo = ? AND mot_de_passe = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $pseudo, $mot_de_passe);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    // Créer un nouvel utilisateur
    public function register($nom, $prenom, $age, $pseudo, $mot_de_passe, $niveau) {
        // Vérifier si le pseudo existe déjà
        if ($this->pseudoExists($pseudo)) {
            return false;
        }
        
        $query = "INSERT INTO {$this->table} (nom, prenom, age, pseudo, niveau, mot_de_passe) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssisss", $nom, $prenom, $age, $pseudo, $niveau, $mot_de_passe);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    // Vérifier si un pseudo existe déjà
    public function pseudoExists($pseudo) {
        $query = "SELECT idJoueur FROM {$this->table} WHERE pseudo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $pseudo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    // Récupérer un utilisateur par son ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE idJoueur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    // Récupérer un utilisateur par son pseudo
    public function getByPseudo($pseudo) {
        $query = "SELECT * FROM {$this->table} WHERE pseudo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $pseudo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    // Mettre à jour le rôle d'un utilisateur
    public function updateRole($userId, $newRole) {
        $query = "UPDATE {$this->table} SET role = ? WHERE idJoueur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $newRole, $userId);
        
        return $stmt->execute();
    }
    
    // Récupérer tous les utilisateurs
    public function getAll() {
        $query = "SELECT idJoueur, pseudo, role FROM {$this->table}";
        $result = $this->db->query($query);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    // Récupérer les valeurs possibles pour le rôle
    public function getRoles() {
        $query = "SHOW COLUMNS FROM {$this->table} LIKE 'role'";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        
        // Extraire les valeurs entre apostrophes à l'aide d'une expression régulière
        preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
        
        // Séparer les valeurs en utilisant "','" comme séparateur
        return explode("','", $matches[1]);
    }
    
    // Ajouter un nouveau rôle
    public function addRole($newRoleName) {
        // Récupérer les rôles actuels
        $currentRoles = $this->getRoles();
        
        // Vérifier si le rôle existe déjà
        if (in_array($newRoleName, $currentRoles)) {
            return false;
        }
        
        // Ajouter le nouveau rôle
        $newRoles = array_merge($currentRoles, [$newRoleName]);
        $enumString = "'" . implode("','", $newRoles) . "'";
        
        $query = "ALTER TABLE {$this->table} MODIFY role ENUM($enumString) NOT NULL DEFAULT 'utilisateur'";
        
        return $this->db->query($query);
    }
}
?>
