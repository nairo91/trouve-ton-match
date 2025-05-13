<?php
require_once 'models/Team.php';
require_once 'utils/Session.php';
require_once 'utils/Validator.php';

class TeamController {
    private $teamModel;
    private $validator;
    
    public function __construct() {
        $this->teamModel = new Team();
        $this->validator = new Validator();
    }
    
    // Afficher la page de création d'équipe
    public function create() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $message = '';
        
        // Traiter le formulaire de création d'équipe
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomEquipe = isset($_POST['nomEquipe']) ? $_POST['nomEquipe'] : '';
            
            // Validation
            $this->validator->required($nomEquipe, 'nomEquipe');
            
            if (!$this->validator->hasErrors()) {
                // Créer l'équipe
                $idEquipe = $this->teamModel->create($nomEquipe);
                
                if ($idEquipe) {
                    // Ajouter le créateur à l'équipe
                    $idJoueur = Session::get('idJoueur');
                    if ($this->teamModel->addPlayer($idEquipe, $idJoueur)) {
                        $message = "Équipe créée avec succès et vous avez rejoint cette équipe.";
                    } else {
                        $message = "Équipe créée mais erreur lors de l'ajout du joueur.";
                    }
                } else {
                    $message = "Erreur lors de la création de l'équipe.";
                }
            } else {
                $message = "Veuillez saisir un nom d'équipe valide.";
            }
        }
        
        // Afficher la vue de création d'équipe
        require_once 'views/team/create.php';
    }
    
    // Afficher la page pour rejoindre une équipe
    public function join() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $message = '';
        $messageClass = '';
        $teams = $this->teamModel->getAll();
        
        // Traiter le formulaire pour rejoindre une équipe
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idEquipe = isset($_POST['idEquipe']) ? intval($_POST['idEquipe']) : 0;
            
            // Validation
            $this->validator->integer($idEquipe, 'idEquipe');
            $this->validator->min($idEquipe, 'idEquipe', 1);
            
            if (!$this->validator->hasErrors()) {
                $idJoueur = Session::get('idJoueur');
                
                // Vérifier si le joueur est déjà dans l'équipe
                if ($this->teamModel->playerIsInTeam($idJoueur, $idEquipe)) {
                    $message = "Vous faites déjà partie de cette équipe.";
                    $messageClass = "error";
                } else {
                    // Ajouter le joueur à l'équipe
                    if ($this->teamModel->addPlayer($idEquipe, $idJoueur)) {
                        $message = "Vous avez rejoint l'équipe avec succès.";
                        $messageClass = "success";
                    } else {
                        $message = "Erreur lors de l'ajout à l'équipe.";
                        $messageClass = "error";
                    }
                }
            } else {
                $message = "Veuillez sélectionner une équipe valide.";
                $messageClass = "error";
            }
        }
        
        // Afficher la vue pour rejoindre une équipe
        require_once 'views/team/join.php';
    }
    
    // Afficher les équipes d'un joueur
    public function myTeams() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $idJoueur = Session::get('idJoueur');
        $teams = $this->teamModel->getTeamsByPlayer($idJoueur);
        
        // Afficher la vue des équipes du joueur
        require_once 'views/team/my_teams.php';
    }
    
    // Afficher les détails d'une équipe
    public function details($id = null) {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        // Récupérer l'ID de l'équipe
        $idEquipe = $id ?? (isset($_GET['id']) ? intval($_GET['id']) : 0);
        
        if ($idEquipe <= 0) {
            header('Location: index.php?controller=Team&action=myTeams');
            exit();
        }
        
        // Récupérer les détails de l'équipe
        $team = $this->teamModel->getById($idEquipe);
        
        if (!$team) {
            header('Location: index.php?controller=Team&action=myTeams');
            exit();
        }
        
        // Récupérer les joueurs de l'équipe
        $players = $this->teamModel->getPlayersByTeam($idEquipe);
        
        // Afficher la vue des détails de l'équipe
        require_once 'views/team/details.php';
    }
    
    // Quitter une équipe
    public function leave() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $message = '';
        $messageClass = '';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idEquipe = isset($_POST['idEquipe']) ? intval($_POST['idEquipe']) : 0;
            
            if ($idEquipe > 0) {
                $idJoueur = Session::get('idJoueur');
                
                // Supprimer le joueur de l'équipe
                $query = "DELETE FROM appartenir WHERE idJoueur = ? AND idEquipe = ?";
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare($query);
                $stmt->bind_param("ii", $idJoueur, $idEquipe);
                
                if ($stmt->execute()) {
                    $message = "Vous avez quitté l'équipe avec succès.";
                    $messageClass = "success";
                } else {
                    $message = "Erreur lors de la sortie de l'équipe.";
                    $messageClass = "error";
                }
            }
        }
        
        // Rediriger vers la liste des équipes
        header('Location: index.php?controller=Team&action=myTeams&message=' . urlencode($message) . '&messageClass=' . $messageClass);
        exit();
    }
}
?>
