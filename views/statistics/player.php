<?php
$pageTitle = "Statistiques du joueur - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Statistiques du joueur</h1>

<?php if (empty($playerStats)): ?>
    <div class="alert alert-info">
        <p>Ce joueur n'a participé à aucun match.</p>
    </div>
<?php else: ?>
    <div class="stats-summary">
        <h2>Résumé des statistiques</h2>
        
        <div class="totals">
            <div class="total-card">
                <div class="total-value"><?php echo $totalStats['total_matchs']; ?></div>
                <div class="total-label">Matchs joués</div>
            </div>
            
            <div class="total-card">
                <div class="total-value"><?php echo $totalStats['total_buts']; ?></div>
                <div class="total-label">Buts</div>
            </div>
            
            <div class="total-card">
                <div class="total-value"><?php echo $totalStats['total_passes']; ?></div>
                <div class="total-label">Passes décisives</div>
            </div>
            
            <div class="total-card">
                <div class="total-value"><?php echo $totalStats['total_arrets']; ?></div>
                <div class="total-label">Arrêts</div>
            </div>
        </div>
        
        <h3>Statistiques par match</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Match</th>
                        <th>Type</th>
                        <th>Ville</th>
                        <th>Buts</th>
                        <th>Passes décisives</th>
                        <th>Arrêts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($playerStats as $match): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($match['nomMatch'] ?? 'Match sans nom'); ?></td>
                            <td><?php echo htmlspecialchars($match['type_match'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($match['ville'] ?? '-'); ?></td>
                            <td><?php echo $match['nombre_buts'] ?? '0'; ?></td>
                            <td><?php echo $match['nombre_passes_decisives'] ?? '0'; ?></td>
                            <td><?php echo $match['nombre_arrets'] ?? '0'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<a href="index.php?controller=Match&action=statistics" class="btn">Gérer mes statistiques</a>
<a href="index.php?controller=User&action=dashboard" class="btn btn-secondary">Retour à l'accueil</a>

<style>
h1, h2, h3 {
    text-align: center;
}

.alert {
    padding: 15px;
    margin: 20px 0;
    border-radius: 5px;
    text-align: center;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.stats-summary {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 30px;
}

.totals {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
    margin: 30px 0;
}

.total-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px 30px;
    text-align: center;
    min-width: 150px;
    transition: transform 0.2s;
}

.total-card:hover {
    transform: translateY(-5px);
}

.total-value {
    font-size: 36px;
    font-weight: bold;
    color: #0077cc;
    margin-bottom: 10px;
}

.total-label {
    font-size: 16px;
    color: #6c757d;
}

.table-responsive {
    overflow-x: auto;
}

.btn-secondary {
    background-color: #6c757d;
    margin-left: 10px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

@media (max-width: 768px) {
    .totals {
        flex-direction: column;
        align-items: center;
    }
    
    .total-card {
        width: 100%;
        max-width: 200px;
    }
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
