<?php
$pageTitle = "Tableau de bord statistique - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Tableau de bord de l'Analyste</h1>

<div class="dashboard-container">
    <div class="stats-card">
        <h2>Statistiques sur les matchs</h2>
        <p><strong>Nombre total de matchs joués:</strong> <?php echo $totalMatchs; ?></p>
        <p><strong>Moyenne de buts par match:</strong> <?php echo $moyenneButs; ?></p>
        <?php if (isset($matchPlusScore)): ?>
            <p><strong>Match le plus scoré:</strong> <?php echo $matchPlusScore['equipe1']; ?> VS <?php echo $matchPlusScore['equipe2']; ?> avec <?php echo $matchPlusScore['total_buts']; ?> buts cumulés</p>
        <?php endif; ?>
    </div>

    <div class="stats-card">
        <h2>Statistiques sur les tournois</h2>
        <p><strong>Nombre total de tournois organisés:</strong> <?php echo $totalTournois; ?></p>
    </div>

    <div class="stats-card full-width">
        <h2>Participation par équipe</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Équipe</th>
                        <th>Nom de l'équipe</th>
                        <th>Tournois participés</th>
                        <th>Total de tournois</th>
                        <th>Taux de participation (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teamsParticipation as $team): ?>
                        <tr>
                            <td><?php echo $team['idEquipe']; ?></td>
                            <td><?php echo htmlspecialchars($team['nomEquipe']); ?></td>
                            <td><?php echo $team['nbParticipations']; ?></td>
                            <td><?php echo $totalTournois; ?></td>
                            <td><?php echo round($team['rate'], 2); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="export-pdf">
            <a href="index.php?controller=Statistics&action=exportGlobalPdf" class="btn btn-export">Exporter en PDF</a>
        </div>
    </div>
</div>

<a href="index.php?controller=User&action=dashboard" class="btn">Retour à l'accueil</a>

<style>
h1, h2 {
    text-align: center;
}

.dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.stats-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.full-width {
    grid-column: 1 / -1;
}

.stats-card h2 {
    color: #0077cc;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.stats-card p {
    font-size: 16px;
    margin-bottom: 10px;
}

.table-responsive {
    overflow-x: auto;
    margin-bottom: 20px;
}

.export-pdf {
    text-align: center;
    margin-top: 20px;
}

.btn-export {
    background-color: #28a745;
}

.btn-export:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    .dashboard-container {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
