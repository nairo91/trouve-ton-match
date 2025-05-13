<?php
$pageTitle = "Mes matchs - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Mes Matchs</h1>

<?php if (empty($matches)): ?>
    <div class="alert alert-info">
        Vous ne participez à aucun match actuellement. 
        <a href="index.php?controller=Match&action=join">Rejoindre un match</a> ou 
        <a href="index.php?controller=Match&action=create">créer un nouveau match</a>.
    </div>
<?php else: ?>
    <div class="matches-container">
        <?php foreach ($matches as $match): ?>
            <div class="match-card">
                <h3><?php echo htmlspecialchars($match['nomMatch'] ?? 'Match sans nom'); ?></h3>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($match['type_match'] ?? '-'); ?></p>
                <p><strong>Ville:</strong> <?php echo htmlspecialchars($match['ville'] ?? '-'); ?></p>
                <p><strong>Joueurs recherchés:</strong> <?php echo htmlspecialchars($match['nombre_joueurs_recherches'] ?? '0'); ?></p>
                
                <div class="statistics">
                    <p><span>Buts:</span> <?php echo $match['nombre_buts'] ?? '0'; ?></p>
                    <p><span>Passes décisives:</span> <?php echo $match['nombre_passes_decisives'] ?? '0'; ?></p>
                    <p><span>Arrêts:</span> <?php echo $match['nombre_arrets'] ?? '0'; ?></p>
                </div>
                
                <div class="actions">
                    <form method="POST" action="index.php?controller=Match&action=leave">
                        <input type="hidden" name="idMatch" value="<?php echo $match['idMatch']; ?>">
                        <button type="submit" class="btn btn-danger">Quitter le match</button>
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

.matches-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.match-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.match-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.match-card h3 {
    color: #00aaff;
    margin-top: 0;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.statistics {
    background-color: #f9f9f9;
    padding: 10px;
    border-radius: 5px;
    margin: 15px 0;
}

.statistics p {
    margin: 5px 0;
}

.statistics span {
    font-weight: bold;
    color: #555;
}

.actions {
    margin-top: 15px;
    text-align: center;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
