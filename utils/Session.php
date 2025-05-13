<?php
class Session {
    // Initialiser la session
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Définir une variable de session
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    // Récupérer une variable de session
    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    
    // Vérifier si une variable de session existe
    public static function exists($key) {
        return isset($_SESSION[$key]);
    }
    
    // Supprimer une variable de session
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    // Détruire la session
    public static function destroy() {
        session_destroy();
    }
    
    // Vérifier si l'utilisateur est connecté
    public static function isLoggedIn() {
        return self::exists('pseudo');
    }
    
    // Vérifier le rôle de l'utilisateur
    public static function hasRole($role) {
        return self::isLoggedIn() && self::get('role') === $role;
    }
    
    // Rediriger si non connecté
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
    }
    
    // Rediriger si pas le rôle requis
    public static function requireRole($role) {
        self::requireLogin();
        if (!self::hasRole($role)) {
            header('Location: index.php?controller=User&action=accessDenied');
            exit();
        }
    }
}
?>
