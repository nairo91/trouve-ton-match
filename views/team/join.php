<?php
$pageTitle = "Rejoindre une équipe - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Rejoindre une Équipe</h1>

<form method="POST" action="index.php?controller=Team&action=join">
    <div class="form-group">
        <label for="idEquipe">Choisir une équipe :</label>
        <select name="idEquipe" id="idEquipe" required>
            <option value="">-- Sélectionnez une équipe --</option>
            <?php foreach ($teams as $team): ?>
                <option value="<?php echo $team['idEquipe']; ?>">
                    <?php echo htmlspecialchars($team['nomEquipe']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <input type="submit" value="Rejoindre" class="btn">
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