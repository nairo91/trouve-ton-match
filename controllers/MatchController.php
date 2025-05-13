<?php
require_once 'models/FootballMatch.php';
require_once 'models/Team.php';
require_once 'utils/Session.php';
require_once 'utils/Validator.php';

class MatchController {
    private $matchModel;
    private $teamModel;
    private $validator;
    
    public function __construct() {
        $this->matchModel = new FootballMatch();
        $this->teamModel = new Team();
        $this->validator = new Validator();
    }
    
    // Afficher la page de création de match
    public function create() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $message = '';
        $messageClass = '';
        
        // Traiter le formulaire de création de match
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomMatch = isset($_POST['nom_match']) ? $_POST['nom_match'] : '';
            $type_match = isset($_POST['type_match']) ? $_POST['type_match'] : '';
            $nombre_joueurs_recherches = isset($_POST['nombre_joueurs_recherches']) ? intval($_POST['nombre_joueurs_recherches']) : 0;
            $ville = isset($_POST['ville']) ? $_POST['ville'] : '';
            
            // Validation
            $this->validator->required($nomMatch, 'nom_match');
            $this->validator->required($type_match, 'type_match');
            $this->validator->required($ville, 'ville');
            $this->validator->integer($nombre_joueurs_recherches, 'nombre_joueurs_recherches');
            $this->validator->min($nombre_joueurs_recherches, 'nombre_joueurs_recherches', 1);
            
            if (!$this->validator->hasErrors()) {
                // Obtenir la connexion à la base de données
                $db = Database::getInstance()->getConnection();
                
                // Débuter une transaction
                $db->begin_transaction();
                
                try {
                    // Créer le match
                    $idMatch = $this->matchModel->create($nomMatch, $type_match, $nombre_joueurs_recherches, $ville);
                    
                    if (!$idMatch) {
                        throw new Exception("Erreur lors de la création du match.");
                    }
                    
                    // Créer automatiquement une équipe avec le même nom
                    if (!$this->matchModel->createTeamForMatch($idMatch, $type_match)) {
                        throw new Exception("Erreur lors de la création de l'équipe.");
                    }
                    
                    // Récupérer l'id du joueur connecté
                    $idJoueur = Session::get('idJoueur');
                    
                    // Si idJoueur n'est pas disponible, essayer de le récupérer depuis le pseudo
                    if (!$idJoueur) {
                        $pseudo = Session::get('pseudo');
                        if ($pseudo) {
                            // Récupérer l'ID du joueur à partir du pseudo
                            $query = "SELECT idJoueur FROM Joueurs WHERE pseudo = ?";
                            $stmt = $db->prepare($query);
                            $stmt->bind_param("s", $pseudo);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($row = $result->fetch_assoc()) {
                                $idJoueur = $row['idJoueur'];
                                // Stocker l'ID du joueur dans la session pour une utilisation future
                                Session::set('idJoueur', $idJoueur);
                            } else {
                                throw new Exception("Impossible de trouver votre ID de joueur.");
                            }
                        } else {
                            throw new Exception("Vous n'êtes pas correctement connecté.");
                        }
                    }
                    
                    // Récupérer l'ID de l'équipe associée au match
                    $query = "SELECT idEquipe FROM Matchs WHERE idMatch = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("i", $idMatch);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        $idEquipe = $row['idEquipe'];
                        
                        // Ajouter le joueur à l'équipe du match
                        $query = "INSERT INTO appartenir (idJoueur, idEquipe) VALUES (?, ?)";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("ii", $idJoueur, $idEquipe);
                        
                        if (!$stmt->execute()) {
                            throw new Exception("Erreur lors de l'ajout du joueur à l'équipe.");
                        }
                        
                        // Ajouter le joueur au match
                        if (!$this->matchModel->addPlayer($idMatch, $idJoueur)) {
                            throw new Exception("Erreur lors de l'ajout du joueur au match.");
                        }
                    } else {
                        throw new Exception("Impossible de trouver l'équipe associée au match.");
                    }
                    
                    // Valider la transaction
                    $db->commit();
                    
                    $message = "Le match '{$nomMatch}' a été créé et vous avez été automatiquement ajouté.";
                    $messageClass = "success";
                    
                } catch (Exception $e) {
                    // Annuler la transaction en cas d'erreur
                    $db->rollback();
                    $message = $e->getMessage();
                    $messageClass = "error";
                }
            } else {
                $message = "Veuillez corriger les erreurs dans le formulaire.";
                $messageClass = "error";
            }
        }
        
        // Afficher la vue de création de match
        require_once 'views/match/create.php';
    }
    
    
    // Afficher la page pour rejoindre un match
    public function join() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $message = '';
        $messageClass = '';
        $matches = $this->matchModel->getAll();
        
        // Traiter le formulaire pour rejoindre un match
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idMatch = isset($_POST['idMatch']) ? intval($_POST['idMatch']) : 0;
            
            // Validation
            $this->validator->integer($idMatch, 'idMatch');
            $this->validator->min($idMatch, 'idMatch', 1);
            
            if (!$this->validator->hasErrors()) {
                $idJoueur = Session::get('idJoueur');
                
                // Vérifier si le joueur participe déjà au match
                if ($this->matchModel->playerIsInMatch($idJoueur, $idMatch)) {
                    $message = "Vous êtes déjà inscrit à ce match.";
                    $messageClass = "error";
                } else {
                    // Ajouter le joueur au match
                    if ($this->matchModel->addPlayer($idMatch, $idJoueur)) {
                        $message = "Vous avez rejoint le match avec succès.";
                        $messageClass = "success";
                    } else {
                        $message = "Erreur lors de l'ajout au match.";
                        $messageClass = "error";
                    }
                }
            } else {
                $message = "Veuillez sélectionner un match valide.";
                $messageClass = "error";
            }
        }
        
        // Afficher la vue pour rejoindre un match
        require_once 'views/match/join.php';
    }
    
    // Afficher les matchs d'un joueur
    public function myMatches() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $idJoueur = Session::get('idJoueur');
        $matches = $this->matchModel->getMatchesByPlayer($idJoueur);
        
        // Afficher la vue des matchs du joueur
        require_once 'views/match/my_matches.php';
    }
    
    // Afficher la page des statistiques d'un joueur pour ses matchs
  // Afficher la page des statistiques d'un joueur pour ses matchs
  public function statistics() {
    // Vérifier que l'utilisateur est connecté
    Session::requireLogin();
    
    $idJoueur = Session::get('idJoueur');
    $pseudo = Session::get('pseudo');
    $matches = $this->matchModel->getMatchesByPlayer($idJoueur);
    $message = '';
    
    // Traiter le formulaire de mise à jour des statistiques
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_match = isset($_POST['id_match']) ? intval($_POST['id_match']) : 0;
        $nombre_buts = isset($_POST['nombre_buts']) ? intval($_POST['nombre_buts']) : 0;
        $nombre_passes_decisives = isset($_POST['nombre_passes_decisives']) ? intval($_POST['nombre_passes_decisives']) : 0;
        $nombre_arrets = isset($_POST['nombre_arrets']) ? intval($_POST['nombre_arrets']) : 0;
        
        // Validation de base
        if ($id_match > 0) {
            try {
                $db = Database::getInstance()->getConnection();
                $query = "UPDATE participer 
                          SET nombre_buts = ?, nombre_passes_decisives = ?, nombre_arrets = ? 
                          WHERE idJoueur = ? AND idMatch = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("iiiii", $nombre_buts, $nombre_passes_decisives, $nombre_arrets, $idJoueur, $id_match);
                
                if ($stmt->execute()) {
                    $message = "Statistiques mises à jour avec succès.";
                    // Rafraîchir les matchs avec les nouvelles statistiques
                    $matches = $this->matchModel->getMatchesByPlayer($idJoueur);
                } else {
                    $message = "Erreur lors de la mise à jour des statistiques.";
                }
            } catch (Exception $e) {
                $message = "Une erreur est survenue lors de la mise à jour.";
                // Log de l'erreur pour le débogage
                error_log("Erreur mise à jour stats: " . $e->getMessage());
            }
        } else {
            $message = "Match invalide.";
        }
    }
    
    // Afficher la vue des statistiques
    require_once 'views/match/statistics.php';
}


    // Quitter un match
    public function leave() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        $message = '';
        $messageClass = '';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idMatch = isset($_POST['idMatch']) ? intval($_POST['idMatch']) : 0;
            
            if ($idMatch > 0) {
                $idJoueur = Session::get('idJoueur');
                
                // Supprimer le joueur du match
                $query = "DELETE FROM participer WHERE idJoueur = ? AND idMatch = ?";
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare($query);
                $stmt->bind_param("ii", $idJoueur, $idMatch);
                
                if ($stmt->execute()) {
                    $message = "Vous avez quitté le match avec succès.";
                    $messageClass = "success";
                } else {
                    $message = "Erreur lors de la sortie du match.";
                    $messageClass = "error";
                }
            }
        }
        
        // Rediriger vers la liste des matchs
        header('Location: index.php?controller=Match&action=myMatches&message=' . urlencode($message) . '&messageClass=' . $messageClass);
        exit();
    }
}
?>
