<?php
require_once 'config/database.php';

class Team {
    private $db;
    private $table = "Equipe";
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Créer une nouvelle équipe
    public function create($nomEquipe) {
        $query = "INSERT INTO {$this->table} (nomEquipe) VALUES (?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $nomEquipe);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    // Ajouter un joueur à une équipe
    public function addPlayer($idEquipe, $idJoueur) {
        // Vérifier si le joueur est déjà dans l'équipe
        if ($this->playerIsInTeam($idJoueur, $idEquipe)) {
            return false;
        }
        
        $query = "INSERT INTO appartenir (idJoueur, idEquipe) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $idJoueur, $idEquipe);
        
        return $stmt->execute();
    }
    
    // Vérifier si un joueur est déjà dans une équipe
    public function playerIsInTeam($idJoueur, $idEquipe) {
        $query = "SELECT * FROM appartenir WHERE idJoueur = ? AND idEquipe = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $idJoueur, $idEquipe);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    // Récupérer toutes les équipes
    public function getAll() {
        $query = "SELECT idEquipe, nomEquipe FROM {$this->table}  WHERE nomEquipe NOT IN ('Amical', 'Compétition', 'Tournoi', 'Exhibition')";
        $result = $this->db->query($query);
        
        $teams = [];
        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }
        
        return $teams;
    }
    
    // Récupérer une équipe par son ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE idEquipe = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    // Récupérer les équipes d'un joueur
    public function getTeamsByPlayer($idJoueur) {
        $query = "SELECT e.* FROM {$this->table} e
                  JOIN appartenir a ON e.idEquipe = a.idEquipe
                  WHERE a.idJoueur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idJoueur);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $teams = [];
        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }
        
        return $teams;
    }
    
    // Récupérer les joueurs d'une équipe
    public function getPlayersByTeam($idEquipe) {
        $query = "SELECT j.* FROM joueurs j
                  JOIN appartenir a ON j.idJoueur = a.idJoueur
                  WHERE a.idEquipe = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idEquipe);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $players = [];
        while ($row = $result->fetch_assoc()) {
            $players[] = $row;
        }
        
        return $players;
    }
}
?>
