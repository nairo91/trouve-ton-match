<?php
$pageTitle = "Détails de l'équipe - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1><?php echo htmlspecialchars($team['nomEquipe']); ?></h1>

<div class="team-details">
    <h2>Membres de l'équipe</h2>
    
    <?php if (empty($players)): ?>
        <p class="no-players">Cette équipe n'a pas encore de membres.</p>
    <?php else: ?>
        <div class="players-list">
            <?php foreach ($players as $player): ?>
                <div class="player-card">
                    <h3><?php echo htmlspecialchars($player['pseudo']); ?></h3>
                    <p><strong>Nom:</strong> <?php echo htmlspecialchars($player['nom'] . ' ' . $player['prenom']); ?></p>
                    <p><strong>Niveau:</strong> <?php echo htmlspecialchars($player['niveau']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="back-buttons">
    <a href="index.php?controller=Team&action=myTeams" class="btn">Retour à mes équipes</a>
    <a href="index.php?controller=User&action=dashboard" class="btn btn-secondary">Retour à l'accueil</a>
</div>

<style>
h1, h2 {
    text-align: center;
    margin-bottom: 30px;
}

.team-details {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 30px;
}

.no-players {
    text-align: center;
    font-style: italic;
    color: #6c757d;
}

.players-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.player-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    transition: transform 0.2s;
}

.player-card:hover {
    transform: translateY(-3px);
}

.player-card h3 {
    color: #00aaff;
    margin-top: 0;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.back-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>