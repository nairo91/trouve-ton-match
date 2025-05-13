<?php
require_once 'models/Statistics.php';
require_once 'utils/Session.php';

class StatisticsController {
    private $statisticsModel;
    
    public function __construct() {
        $this->statisticsModel = new Statistics();
    }
    
    // Afficher le tableau de bord de l'analyste
    public function dashboard() {
        // Vérifier que l'utilisateur est analyste
        Session::requireRole('analyste');
        
        // Récupérer les statistiques globales
        $totalMatchs = $this->statisticsModel->getTotalMatches();
        $moyenneButs = $this->statisticsModel->getAverageGoalsPerMatch();
        $matchPlusScore = $this->statisticsModel->getMostScoredMatch();
        $totalTournois = $this->statisticsModel->getTotalTournaments();
        $teamsParticipation = $this->statisticsModel->getTeamsParticipation();
        
        // Afficher le tableau de bord de l'analyste
        require_once 'views/statistics/dashboard.php';
    }
    
    // Afficher les statistiques d'un joueur
    public function player($id = null) {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        // Récupérer l'ID du joueur (celui connecté ou spécifié)
        $idJoueur = $id ?? Session::get('idJoueur');
        
        // Récupérer les statistiques du joueur
        $playerStats = $this->statisticsModel->getPlayerStatistics($idJoueur);
        $totalStats = $this->statisticsModel->getPlayerTotalStatistics($idJoueur);
        
        // Afficher les statistiques du joueur
        require_once 'views/statistics/player.php';
    }
    
    // Afficher les statistiques d'un tournoi
    public function tournament($id = null) {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        // Vérifier si un ID de tournoi est spécifié
        if (!$id && !isset($_GET['id'])) {
            header('Location: index.php?controller=Tournament&action=show');
            exit();
        }
        
        $idTournois = $id ?? intval($_GET['id']);
        
        // Récupérer le tournoi
        require_once 'models/Tournament.php';
        $tournamentModel = new Tournament();
        $tournament = $tournamentModel->getById($idTournois);
        
        if (!$tournament) {
            header('Location: index.php?controller=Tournament&action=show');
            exit();
        }
        
        // Récupérer les statistiques des équipes pour ce tournoi
        $teamStats = $tournamentModel->getTeamStatistics($idTournois);
        
        // Récupérer les matchs du tournoi
        $phases = ['Quart de finale', 'Demi-finale', 'Finale'];
        $matchesByPhase = [];
        
        foreach ($phases as $phase) {
            $matchesByPhase[$phase] = $tournamentModel->getMatchesByPhase($idTournois, $phase);
        }
        
        // Afficher les statistiques du tournoi
        require_once 'views/statistics/tournament.php';
    }
    
    // Exporter les statistiques globales en PDF
    public function exportGlobalPdf() {
        // Vérifier que l'utilisateur est analyste
        Session::requireRole('analyste');
        
        // Rediriger vers le contrôleur PDF pour l'export
        header("Location: index.php?controller=Pdf&action=exportGlobalStats");
        exit();
    }
}
?>
