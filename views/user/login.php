<?php
$pageTitle = "Connexion - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Connexion</h1>

<form method="POST" action="index.php?controller=User&action=login">
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" required>
    </div>
    
    <div class="form-group">
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    </div>
    
    <div class="form-group">
        <input type="submit" value="Se connecter">
    </div>
</form>

<p class="text-center">
    Vous n'avez pas de compte ? <a href="index.php?controller=User&action=register">Inscrivez-vous</a>
</p>

<style>
h1 {
    text-align: center;
    margin-bottom: 30px;
}

form {
    max-width: 400px;
}

.text-center {
    text-align: center;
    margin-top: 20px;
}

.text-center a {
    color: #00aaff;
    text-decoration: none;
}

.text-center a:hover {
    text-decoration: underline;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
