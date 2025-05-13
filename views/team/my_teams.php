<?php
$pageTitle = "Mes équipes - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Mes Équipes</h1>

<?php if (empty($teams)): ?>
    <div class="alert alert-info">
        Vous ne faites partie d'aucune équipe actuellement. 
        <a href="index.php?controller=Team&action=join">Rejoindre une équipe</a> ou 
        <a href="index.php?controller=Team&action=create">créer une nouvelle équipe</a>.
    </div>
<?php else: ?>
    <div class="teams-container">
        <?php foreach ($teams as $team): ?>
            <div class="team-card">
                <h3><?php echo htmlspecialchars($team['nomEquipe']); ?></h3>
                <div class="team-actions">
                    <a href="index.php?controller=Team&action=details&id=<?php echo $team['idEquipe']; ?>" class="btn btn-info">Détails</a>
                    <form method="POST" action="index.php?controller=Team&action=leave">
                        <input type="hidden" name="idEquipe" value="<?php echo $team['idEquipe']; ?>">
                        <button type="submit" class="btn btn-danger">Quitter</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<a href="index.php?controller=User&action=dashboard" class="btn">Retour à l'accueil</a>

<style>
h1 {
    text-align: center;
    margin-bottom: 30px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.teams-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.team-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
    transition: transform 0.2s, box-shadow 0.2s;
    text-align: center;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.team-card h3 {
    color: #00aaff;
    margin-top: 0;
    margin-bottom: 20px;
}

.team-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-info {
    background-color: #17a2b8;
}

.btn-info:hover {
    background-color: #138496;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>