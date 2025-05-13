<?php
$pageTitle = "Créer une équipe - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Créer une équipe</h1>

<form method="post" action="index.php?controller=Team&action=create">
    <div class="form-group">
        <label for="nomEquipe">Nom de l'équipe :</label>
        <input type="text" name="nomEquipe" id="nomEquipe" required>
    </div>
    
    <div class="form-group">
        <input type="submit" value="Créer l'équipe" class="btn">
    </div>
</form>

<a href="index.php?controller=User&action=dashboard" class="btn btn-secondary">Retour à l'accueil</a>

<style>
h1 {
    text-align: center;
    margin-bottom: 30px;
}

form {
    max-width: 500px;
}

.btn-secondary {
    background-color: #6c757d;
    display: inline-block;
    margin-top: 20px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>