<?php
require_once 'models/Tournament.php';
require_once 'models/Team.php';
require_once 'utils/Session.php';
require_once 'utils/Validator.php';

class TournamentController {
    private $tournamentModel;
    private $teamModel;
    private $validator;
    
    public function __construct() {
        $this->tournamentModel = new Tournament();
        $this->teamModel = new Team();
        $this->validator = new Validator();
    }
    
    // Afficher la page de création de tournoi
    public function create() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $teams = $this->teamModel->getAll();
        $message = '';
        $messageClass = '';
        
        // Traiter le formulaire de création de tournoi
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomTournoi = isset($_POST['nomTournoi']) ? $_POST['nomTournoi'] : '';
            $villeTournoi = isset($_POST['villeTournoi']) ? $_POST['villeTournoi'] : '';
            $equipes = isset($_POST['equipes']) ? $_POST['equipes'] : [];
            
            // Validation
            $this->validator->required($nomTournoi, 'nomTournoi');
            $this->validator->required($villeTournoi, 'villeTournoi');
            
            if (count($equipes) !== 8) {
                $message = "Vous devez sélectionner exactement 8 équipes.";
                $messageClass = "error";
            } else if (!$this->validator->hasErrors()) {
                // Créer le tournoi
                $idTournois = $this->tournamentModel->create($nomTournoi, $villeTournoi);
                
                if ($idTournois) {
                    $success = true;
                    
                    // Ajouter les équipes au tournoi
                    foreach ($equipes as $idEquipe) {
                        if (!$this->tournamentModel->addTeam($idEquipe, $idTournois)) {
                            $success = false;
                            break;
                        }
                    }
                    
                    // Créer les matchs de quart de finale
                    if ($success) {
                        for ($i = 0; $i < count($equipes); $i += 2) {
                            if (isset($equipes[$i + 1])) {
                                $this->tournamentModel->createMatch($idTournois, $equipes[$i], $equipes[$i + 1], 'Quart de finale');
                            }
                        }
                        
                        $message = "Tournoi créé avec succès avec les matchs de quart de finale.";
                        $messageClass = "success";
                    } else {
                        $message = "Erreur lors de l'ajout des équipes au tournoi.";
                        $messageClass = "error";
                    }
                } else {
                    $message = "Erreur lors de la création du tournoi.";
                    $messageClass = "error";
                }
            } else {
                $message = "Veuillez corriger les erreurs dans le formulaire.";
                $messageClass = "error";
            }
        }
        
        // Afficher la vue de création de tournoi
        require_once 'views/tournament/create.php';
    }
    
    // Afficher tous les tournois
    public function show() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $tournaments = $this->tournamentModel->getAll();
        $selectedTournament = null;
        $matches = [];
        $statistics = [];
        
        // Si un tournoi est sélectionné
        if (isset($_GET['tournoi'])) {
            $idTournois = intval($_GET['tournoi']);
            
            // Récupérer le tournoi sélectionné
            $selectedTournament = $this->tournamentModel->getById($idTournois);
            
            if ($selectedTournament) {
                // Récupérer les matchs par phase
                $quartMatches = $this->tournamentModel->getMatchesByPhase($idTournois, 'Quart de finale');
                $demiMatches = $this->tournamentModel->getMatchesByPhase($idTournois, 'Demi-finale');
                $finaleMatches = $this->tournamentModel->getMatchesByPhase($idTournois, 'Finale');
                
                $matches = [
                    'Quart de finale' => $quartMatches,
                    'Demi-finale' => $demiMatches,
                    'Finale' => $finaleMatches
                ];
                
                // Récupérer les statistiques des équipes
                $statistics = $this->tournamentModel->getTeamStatistics($idTournois);
            }
        }
        
        // Afficher la vue des tournois
        require_once 'views/tournament/show.php';
    }
    
    // Gérer les tournois (admin/organisateur)
    public function manage() {
        // Vérifier que l'utilisateur est administrateur
        Session::requireRole('admin');
        
        $tournaments = $this->tournamentModel->getAll();
        $selectedTournament = null;
        $phases = ['Quart de finale', 'Demi-finale', 'Finale'];
        $matchesByPhase = [];
        $message = '';
        
        // Traiter la mise à jour des matchs
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['gagnant'], $_POST['idMatch'], $_POST['scoreEquipe1'], $_POST['scoreEquipe2'])) {
                $idMatch = intval($_POST['idMatch']);
                $gagnant = $_POST['gagnant'];
                $scoreEquipe1 = intval($_POST['scoreEquipe1']);
                $scoreEquipe2 = intval($_POST['scoreEquipe2']);
                
                // Mise à jour du match
                $db = Database::getInstance()->getConnection();
                $queryUpdate = "UPDATE Matchs SET winner = ?, scoreEquipe1 = ?, scoreEquipe2 = ? WHERE idMatch = ?";
                $stmtUpdate = $db->prepare($queryUpdate);
                $stmtUpdate->bind_param("siii", $gagnant, $scoreEquipe1, $scoreEquipe2, $idMatch);
                
                if ($stmtUpdate->execute()) {
                    // Récupérer les informations sur le match mis à jour
                    $queryMatch = "SELECT idTournois, phase FROM Matchs WHERE idMatch = ?";
                    $stmtMatch = $db->prepare($queryMatch);
                    $stmtMatch->bind_param("i", $idMatch);
                    $stmtMatch->execute();
                    $resultMatch = $stmtMatch->get_result();
                    $match = $resultMatch->fetch_assoc();
                    
                    // Générer la phase suivante si tous les matchs de la phase actuelle ont un gagnant
                    if ($match) {
                        $idTournois = $match['idTournois'];
                        $currentPhase = $match['phase'];
                        
                        $this->tournamentModel->createNextPhaseMatches($idTournois, $currentPhase);
                    }
                    
                    $message = "Match mis à jour avec succès.";
                } else {
                    $message = "Erreur lors de la mise à jour du match.";
                }
            }
        }
        
        // Si un tournoi est sélectionné
        if (isset($_GET['tournoi'])) {
            $idTournois = intval($_GET['tournoi']);
            
            // Récupérer le tournoi sélectionné
            $selectedTournament = $this->tournamentModel->getById($idTournois);
            
            if ($selectedTournament) {
                // Récupérer les matchs pour chaque phase
                foreach ($phases as $phase) {
                    $matchesByPhase[$phase] = $this->tournamentModel->getMatchesByPhase($idTournois, $phase);
                }
            }
        }
        
        // Afficher la vue de gestion des tournois
        require_once 'views/tournament/manage.php';
    }
    
    // Exporter les données d'un tournoi en PDF
    public function exportPdf() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        if (isset($_GET['tournoi'])) {
            $idTournois = intval($_GET['tournoi']);
            
            // Rediriger vers le contrôleur PDF pour l'export
            header("Location: index.php?controller=Pdf&action=exportTournament&tournoi={$idTournois}");
            exit();
        } else {
            // Rediriger vers la liste des tournois
            header("Location: index.php?controller=Tournament&action=show");
            exit();
        }
    }
}
?>
