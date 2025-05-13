<?php
$pageTitle = "Statistiques du tournoi - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1><?php echo htmlspecialchars($tournament['nomTournois']); ?></h1>
<p class="tournament-location">
    <strong>Ville:</strong> <?php echo htmlspecialchars($tournament['villeTournois']); ?>
</p>

<div class="tournament-details">
    <div class="bracket-container">
        <h2>Tableau du tournoi</h2>
        
        <div class="bracket">
            <?php foreach ($phases as $phase): ?>
                <div class="phase">
                    <h3><?php echo $phase; ?></h3>
                    
                    <?php if (empty($matchesByPhase[$phase])): ?>
                        <p class="no-matches">Aucun match pour cette phase.</p>
                    <?php else: ?>
                        <div class="phase-matches">
                            <?php foreach ($matchesByPhase[$phase] as $match): ?>
                                <div class="bracket-match">
                                    <div class="team-row <?php echo ($match['winner'] == $match['idEquipe1']) ? 'winner' : ''; ?>">
                                        <span class="team-name"><?php echo htmlspecialchars($match['equipe1']); ?></span>
                                        <span class="team-score"><?php echo $match['scoreEquipe1']; ?></span>
                                    </div>
                                    <div class="team-row <?php echo ($match['winner'] == $match['idEquipe2']) ? 'winner' : ''; ?>">
                                        <span class="team-name"><?php echo htmlspecialchars($match['equipe2']); ?></span>
                                        <span class="team-score"><?php echo $match['scoreEquipe2']; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="stats-container">
        <h2>Statistiques des équipes</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Équipe</th>
                    <th>Victoires</th>
                    <th>Défaites</th>
                    <th>Buts marqués</th>
                    <th>Buts encaissés</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teamStats as $stat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stat['nomEquipe']); ?></td>
                        <td><?php echo $stat['victoires']; ?></td>
                        <td><?php echo $stat['defaites']; ?></td>
                        <td><?php echo $stat['buts_marques']; ?></td>
                        <td><?php echo $stat['buts_encaisses']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="export-pdf">
            <a href="index.php?controller=Tournament&action=exportPdf&tournoi=<?php echo $tournament['idTournois']; ?>" class="btn btn-export">Exporter en PDF</a>
        </div>
    </div>
</div>

<div class="back-links">
    <a href="index.php?controller=Tournament&action=show" class="btn">Voir tous les tournois</a>
    <a href="index.php?controller=User&action=dashboard" class="btn btn-secondary">Retour à l'accueil</a>
</div>

<style>
h1, h2, h3 {
    text-align: center;
}

.tournament-location {
    text-align: center;
    font-size: 18px;
    margin-bottom: 30px;
}

.tournament-details {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

@media (min-width: 992px) {
    .tournament-details {
        grid-template-columns: 1fr 1fr;
    }
}

.bracket-container, .stats-container {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.bracket {
    display: flex;
    overflow-x: auto;
    padding-bottom: 20px;
}

.phase {
    flex: 0 0 250px;
    padding: 0 15px;
}

.phase-matches {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.bracket-match {
    border: 1px solid #dee2e6;
    border-radius: 5px;
    overflow: hidden;
}

.team-row {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.team-row:last-child {
    border-bottom: none;
}

.team-row.winner {
    background-color: #e8f5e9;
    font-weight: bold;
}

.team-name {
    flex: 1;
    padding-right: 10px;
}

.team-score {
    font-weight: bold;
    min-width: 30px;
    text-align: center;
}

.no-matches {
    text-align: center;
    font-style: italic;
    color: #777;
    padding: 20px;
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

.back-links {
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
