<?php
$pageTitle = "Accès refusé - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<div class="access-denied">
    <div class="icon">⚠️</div>
    <h1>Accès refusé</h1>
    <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    <p>Veuillez contacter un administrateur si vous pensez qu'il s'agit d'une erreur.</p>
    <div class="buttons">
        <a href="index.php?controller=User&action=dashboard" class="btn">Retour à l'accueil</a>
        <a href="index.php?controller=User&action=logout" class="btn btn-secondary">Se déconnecter</a>
    </div>
</div>

<style>
.access-denied {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
    padding: 40px 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.icon {
    font-size: 72px;
    margin-bottom: 20px;
}

h1 {
    color: #dc3545;
    margin-bottom: 20px;
}

p {
    font-size: 18px;
    color: #555;
    margin-bottom: 10px;
}

.buttons {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
