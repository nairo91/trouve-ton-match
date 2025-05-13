<?php
require_once 'models/User.php';
require_once 'utils/Session.php';
require_once 'utils/Validator.php';

class UserController {
    private $userModel;
    private $validator;
    
    public function __construct() {
        $this->userModel = new User();
        $this->validator = new Validator();
    }
    
    // Afficher la page d'accueil
    public function index() {
        // Si l'utilisateur est connecté, rediriger vers le dashboard
        if (Session::isLoggedIn()) {
            $this->dashboard();
            return;
        }
        
        // Sinon, afficher la page d'accueil publique
        require_once 'views/user/index.php';
    }
    
    // Afficher la page de connexion
    public function login() {
        // Si déjà connecté, rediriger vers le tableau de bord
        if (Session::isLoggedIn()) {
            header('Location: index.php?controller=User&action=dashboard');
            exit();
        }
        
        $error = null;
        
        // Traiter le formulaire de connexion
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : '';
            $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
            
            // Authentification
            $user = $this->userModel->authenticate($pseudo, $mot_de_passe);
            
            if ($user) {
                // Créer la session
                Session::set('pseudo', $user['pseudo']);
                Session::set('role', $user['role']);
                Session::set('idJoueur', $user['idJoueur']);
                
                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    header("Location: index.php?controller=User&action=adminDashboard");
                } else if ($user['role'] === 'analyste') {
                    header("Location: index.php?controller=Statistics&action=dashboard");
                } else {
                    header("Location: index.php?controller=User&action=dashboard");
                }
                exit();
            } else {
                $error = "Pseudo ou mot de passe incorrect.";
            }
        }
        
        // Afficher la vue de connexion
        require_once 'views/user/login.php';
    }
    
    // Afficher la page d'inscription
    public function register() {
        // Si déjà connecté, rediriger vers le tableau de bord
        if (Session::isLoggedIn()) {
            header('Location: index.php?controller=User&action=dashboard');
            exit();
        }
        
        $error = null;
        
        // Traiter le formulaire d'inscription
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
            $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
            $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
            $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : '';
            $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
            $niveau = isset($_POST['niveau']) ? $_POST['niveau'] : '';
            
            // Validation des champs
            $this->validator->required($nom, 'nom');
            $this->validator->required($prenom, 'prenom');
            $this->validator->required($pseudo, 'pseudo');
            $this->validator->required($mot_de_passe, 'mot_de_passe');
            $this->validator->required($niveau, 'niveau');
            $this->validator->integer($age, 'age');
            $this->validator->min($age, 'age', 5);
            $this->validator->max($age, 'age', 120);
            
            if (!$this->validator->hasErrors()) {
                // Vérifier si le pseudo existe déjà
                if ($this->userModel->pseudoExists($pseudo)) {
                    $error = "Ce pseudo est déjà utilisé.";
                } else {
                    // Créer l'utilisateur
                    $result = $this->userModel->register($nom, $prenom, $age, $pseudo, $mot_de_passe, $niveau);
                    
                    if ($result) {
                        // Rediriger vers la page de connexion
                        header('Location: index.php?controller=User&action=login');
                        exit();
                    } else {
                        $error = "Erreur lors de l'inscription.";
                    }
                }
            } else {
                $error = "Veuillez corriger les erreurs dans le formulaire.";
            }
        }
        
        // Afficher la vue d'inscription
        require_once 'views/user/register.php';
    }
    
    // Afficher le tableau de bord utilisateur
    public function dashboard() {
        // Vérifier que l'utilisateur est connecté
        Session::requireLogin();
        
        // Afficher le tableau de bord
        require_once 'views/user/dashboard.php';
    }
    
    // Afficher le tableau de bord administrateur
    public function adminDashboard() {
        // Vérifier que l'utilisateur est administrateur
        Session::requireRole('admin');
        
        // Afficher le tableau de bord admin
        require_once 'views/user/admin_dashboard.php';
    }
    
    // Afficher la page de gestion des rôles
    public function manageRoles() {
        // Vérifier que l'utilisateur est administrateur
        Session::requireRole('admin');
        
        $message = '';
        $users = $this->userModel->getAll();
        $roles = $this->userModel->getRoles();
        
        // Traitement de la mise à jour du rôle
        if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['update_user'])) {
            $userId = intval($_POST['user_id']);
            $newRole = trim($_POST['role']);
            
            if (in_array($newRole, $roles)) {
                if ($this->userModel->updateRole($userId, $newRole)) {
                    $message = "Rôle mis à jour avec succès.";
                } else {
                    $message = "Erreur lors de la mise à jour du rôle.";
                }
            } else {
                $message = "Le rôle choisi n'est pas valide.";
            }
        }
        
        // Traitement de la création d'un nouveau rôle
        if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['create_role'])) {
            $newRoleName = trim($_POST['new_role']);
            
            if (!empty($newRoleName)) {
                if (!in_array($newRoleName, $roles)) {
                    if ($this->userModel->addRole($newRoleName)) {
                        $message = "Nouveau rôle ajouté avec succès.";
                        $roles = $this->userModel->getRoles(); // Actualiser la liste des rôles
                    } else {
                        $message = "Erreur lors de l'ajout du nouveau rôle.";
                    }
                } else {
                    $message = "Ce rôle existe déjà.";
                }
            } else {
                $message = "Veuillez saisir un rôle valide.";
            }
        }
        
        // Afficher la vue de gestion des rôles
        require_once 'views/user/manage_roles.php';
    }
    
    // Déconnexion
    public function logout() {
        Session::destroy();
        header('Location: index.php');
        exit();
    }
    
    // Page d'accès refusé
    public function accessDenied() {
        require_once 'views/user/access_denied.php';
    }
}
?>
