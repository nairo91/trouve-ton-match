<?php
require_once 'config/database.php';

class FootballMatch {
    private $db;
    private $table = "Matchs";
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Créer un nouveau match
    public function create($nomMatch, $type_match, $nombre_joueurs_recherches, $ville) {
        $query = "INSERT INTO {$this->table} (nomMatch, type_match, nombre_joueurs_recherches, ville) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssis", $nomMatch, $type_match, $nombre_joueurs_recherches, $ville);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    // Créer une équipe pour un match et l'associer au match
    public function createTeamForMatch($idMatch, $nomEquipe) {
        // Créer l'équipe
        $queryEquipe = "INSERT INTO Equipe (nomEquipe) VALUES (?)";
        $stmt = $this->db->prepare($queryEquipe);
        $stmt->bind_param("s", $nomEquipe);
        
        if ($stmt->execute()) {
            $idEquipe = $this->db->insert_id;
            
            // Associer l'équipe au match
            $updateMatch = "UPDATE {$this->table} SET idEquipe = ? WHERE idMatch = ?";
            $stmt = $this->db->prepare($updateMatch);
            $stmt->bind_param("ii", $idEquipe, $idMatch);
            
            return $stmt->execute();
        }
        
        return false;
    }
    
    // Ajouter un joueur à un match
    public function addPlayer($idMatch, $idJoueur) {
        // Vérifier si le joueur participe déjà au match
        if ($this->playerIsInMatch($idJoueur, $idMatch)) {
            return false;
        }
        
        $query = "INSERT INTO participer (idJoueur, idMatch) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $idJoueur, $idMatch);
        
        return $stmt->execute();
    }
    
    // Vérifier si un joueur participe déjà à un match
    public function playerIsInMatch($idJoueur, $idMatch) {
        $query = "SELECT * FROM participer WHERE idJoueur = ? AND idMatch = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $idJoueur, $idMatch);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    // Mettre à jour les statistiques d'un joueur pour un match
    public function updatePlayerStats($idJoueur, $idMatch, $nombre_buts, $nombre_passes_decisives, $nombre_arrets) {
        $query = "UPDATE participer 
                  SET nombre_buts = ?, nombre_passes_decisives = ?, nombre_arrets = ? 
                  WHERE idJoueur = ? AND idMatch = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiiii", $nombre_buts, $nombre_passes_decisives, $nombre_arrets, $idJoueur, $idMatch);
        
        return $stmt->execute();
    }
    
    // Récupérer tous les matchs
 // Récupérer tous les matchs
public function getAll() {
    // Ajout d'une condition WHERE pour exclure les matchs sans nom
    $query = "SELECT idMatch, nomMatch FROM {$this->table} WHERE nomMatch IS NOT NULL AND nomMatch != ''";
    $result = $this->db->query($query);
    
    $matches = [];
    while ($row = $result->fetch_assoc()) {
        $matches[] = $row;
    }
    
    return $matches;
}

    
    // Récupérer un match par son ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE idMatch = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    // Récupérer les matchs d'un joueur
    public function getMatchesByPlayer($idJoueur) {
        $query = "SELECT m.*, p.nombre_buts, p.nombre_passes_decisives, p.nombre_arrets 
                  FROM {$this->table} m
                  JOIN participer p ON m.idMatch = p.idMatch
                  WHERE p.idJoueur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idJoueur);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $matches = [];
        while ($row = $result->fetch_assoc()) {
            $matches[] = $row;
        }
        
        return $matches;
    }
    
    // Mettre à jour le score et le gagnant d'un match
    public function updateScore($idMatch, $scoreEquipe1, $scoreEquipe2, $winner) {
        $query = "UPDATE {$this->table} 
                  SET scoreEquipe1 = ?, scoreEquipe2 = ?, winner = ? 
                  WHERE idMatch = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiis", $scoreEquipe1, $scoreEquipe2, $winner, $idMatch);
        
        return $stmt->execute();
    }
}
?>
