<?php
require_once 'config/database.php';

class Statistics {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Nombre total de matchs
    public function getTotalMatches() {
        $query = "SELECT COUNT(*) AS total_matchs FROM Matchs";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total_matchs'];
    }
    
    // Moyenne de buts par match
    public function getAverageGoalsPerMatch() {
        $query = "SELECT AVG(scoreEquipe1 + scoreEquipe2) AS avg_goals
                  FROM Matchs
                  WHERE scoreEquipe1 IS NOT NULL AND scoreEquipe2 IS NOT NULL";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return round($row['avg_goals'], 2);
    }
    
    // Match le plus scoré
    public function getMostScoredMatch() {
        $query = "SELECT e1.nomEquipe AS equipe1, e2.nomEquipe AS equipe2, 
                  (m.scoreEquipe1 + m.scoreEquipe2) AS total_buts
                  FROM Matchs m
                  LEFT JOIN Equipe e1 ON m.idEquipe1 = e1.idEquipe
                  LEFT JOIN Equipe e2 ON m.idEquipe2 = e2.idEquipe
                  ORDER BY total_buts DESC
                  LIMIT 1";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }
    
    // Nombre total de tournois
    public function getTotalTournaments() {
        $query = "SELECT COUNT(*) AS total_tournois FROM Tournois";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total_tournois'];
    }
    
    // Participation par équipe sur tous les tournois
    public function getTeamsParticipation() {
        $totalTournois = $this->getTotalTournaments();
        
        $query = "SELECT e.idEquipe, e.nomEquipe, 
                  COUNT(DISTINCT i.idTournois) AS nbParticipations
                  FROM Equipe e
                  LEFT JOIN impliquer i ON e.idEquipe = i.idEquipe
                  GROUP BY e.idEquipe";
        $result = $this->db->query($query);
        
        $teamsParticipation = [];
        while ($row = $result->fetch_assoc()) {
            $rate = $totalTournois > 0 ? ($row['nbParticipations'] / $totalTournois) * 100 : 0;
            $teamsParticipation[] = [
                'idEquipe' => $row['idEquipe'],
                'nomEquipe' => $row['nomEquipe'],
                'nbParticipations' => $row['nbParticipations'],
                'rate' => $rate
            ];
        }
        
        return $teamsParticipation;
    }
    
    // Statistiques d'un joueur
    public function getPlayerStatistics($idJoueur) {
        $query = "SELECT m.idMatch, m.nomMatch, m.type_match, m.ville, 
                  p.nombre_buts, p.nombre_passes_decisives, p.nombre_arrets
                  FROM participer p
                  JOIN Matchs m ON p.idMatch = m.idMatch
                  WHERE p.idJoueur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idJoueur);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $statistics = [];
        while ($row = $result->fetch_assoc()) {
            $statistics[] = $row;
        }
        
        return $statistics;
    }
    
    // Statistiques totales d'un joueur
    public function getPlayerTotalStatistics($idJoueur) {
        $query = "SELECT 
                  SUM(nombre_buts) AS total_buts,
                  SUM(nombre_passes_decisives) AS total_passes,
                  SUM(nombre_arrets) AS total_arrets,
                  COUNT(idMatch) AS total_matchs
                  FROM participer
                  WHERE idJoueur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idJoueur);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}
?>
