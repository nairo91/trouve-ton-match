<?php
require_once 'config/database.php';

class Tournament {
    private $db;
    private $table = "Tournois";
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Créer un nouveau tournoi
    public function create($nomTournoi, $villeTournoi) {
        $query = "INSERT INTO {$this->table} (nomTournois, villeTournois) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $nomTournoi, $villeTournoi);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    // Ajouter une équipe à un tournoi
    public function addTeam($idEquipe, $idTournois) {
        $query = "INSERT INTO impliquer (idEquipe, idTournois) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $idEquipe, $idTournois);
        
        return $stmt->execute();
    }
    
    // Créer un match de tournoi
    public function createMatch($idTournois, $idEquipe1, $idEquipe2, $phase) {
        $query = "INSERT INTO Matchs (type_match, idEquipe1, idEquipe2, phase, idTournois) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $type = $phase; // Le type de match est égal à la phase (par exemple "Quart de finale")
        $stmt->bind_param("ssssi", $type, $idEquipe1, $idEquipe2, $phase, $idTournois);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    // Récupérer tous les tournois
    public function getAll() {
        $query = "SELECT idTournois, nomTournois FROM {$this->table}";
        $result = $this->db->query($query);
        
        $tournaments = [];
        while ($row = $result->fetch_assoc()) {
            $tournaments[] = $row;
        }
        
        return $tournaments;
    }
    
    // Récupérer un tournoi par son ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE idTournois = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    // Récupérer les matchs d'un tournoi par phase
    public function getMatchesByPhase($idTournois, $phase) {
        $query = "SELECT m.idMatch, m.idEquipe1, m.idEquipe2, e1.nomEquipe AS equipe1, 
                  e2.nomEquipe AS equipe2, m.winner, m.scoreEquipe1, m.scoreEquipe2,
                  (SELECT nomEquipe FROM Equipe WHERE idEquipe = m.winner) AS winnerName
                  FROM Matchs m 
                  LEFT JOIN Equipe e1 ON m.idEquipe1 = e1.idEquipe
                  LEFT JOIN Equipe e2 ON m.idEquipe2 = e2.idEquipe
                  WHERE m.idTournois = ? AND m.phase = ? 
                  ORDER BY m.idMatch";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $idTournois, $phase);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $matches = [];
        while ($row = $result->fetch_assoc()) {
            $matches[] = $row;
        }
        
        return $matches;
    }
    
    // Créer les matchs de la phase suivante
    public function createNextPhaseMatches($idTournois, $currentPhase) {
        $phases = [
            'Quart de finale' => 'Demi-finale',
            'Demi-finale' => 'Finale'
        ];
        
        if (!isset($phases[$currentPhase])) {
            return false; // Phase inconnue ou finale déjà atteinte
        }
        
        $nextPhase = $phases[$currentPhase];
        
        // Récupérer les gagnants de la phase actuelle
        $query = "SELECT winner FROM Matchs 
                  WHERE idTournois = ? AND phase = ? AND winner IS NOT NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $idTournois, $currentPhase);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $winners = [];
        while ($row = $result->fetch_assoc()) {
            $winners[] = $row['winner'];
        }
        
        // Vérifier si tous les matchs de la phase actuelle ont un gagnant
        $expectedWinners = ($currentPhase == 'Quart de finale') ? 4 : 2;
        if (count($winners) != $expectedWinners) {
            return false;
        }
        
        // Créer les matchs de la phase suivante
        for ($i = 0; $i < count($winners); $i += 2) {
            $this->createMatch($idTournois, $winners[$i], $winners[$i + 1], $nextPhase);
        }
        
        return true;
    }
    
    // Récupérer les statistiques des équipes pour un tournoi
    public function getTeamStatistics($idTournois) {
        $query = "SELECT e.nomEquipe,
                  SUM(CASE WHEN m.winner = e.idEquipe THEN 1 ELSE 0 END) AS victoires,
                  SUM(CASE WHEN m.winner != e.idEquipe AND (m.idEquipe1 = e.idEquipe OR m.idEquipe2 = e.idEquipe) THEN 1 ELSE 0 END) AS defaites,
                  SUM(CASE WHEN m.idEquipe1 = e.idEquipe THEN m.scoreEquipe1 ELSE m.scoreEquipe2 END) AS buts_marques,
                  SUM(CASE WHEN m.idEquipe1 = e.idEquipe THEN m.scoreEquipe2 ELSE m.scoreEquipe1 END) AS buts_encaisses
                  FROM Equipe e
                  LEFT JOIN Matchs m ON e.idEquipe = m.idEquipe1 OR e.idEquipe = m.idEquipe2
                  WHERE m.idTournois = ?
                  GROUP BY e.idEquipe";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idTournois);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $statistics = [];
        while ($row = $result->fetch_assoc()) {
            $statistics[] = $row;
        }
        
        return $statistics;
    }
}
?>
