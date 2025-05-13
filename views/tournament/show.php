<?php
$pageTitle = "Tournois - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Liste des Tournois</h1>

<div class="select-container">
    <form method="GET" action="index.php">
        <input type="hidden" name="controller" value="Tournament">
        <input type="hidden" name="action" value="show">
        <label for="tournoi">Sélectionnez un tournoi :</label>
        <select name="tournoi" id="tournoi" onchange="this.form.submit()">
            <option value="">-- Choisissez un tournoi --</option>
            <?php foreach ($tournaments as $tournament): ?>
                <?php $selected = (isset($_GET['tournoi']) && $_GET['tournoi'] == $tournament['idTournois']) ? 'selected' : ''; ?>
                <option value="<?php echo $tournament['idTournois']; ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($tournament['nomTournois']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($selectedTournament): ?>
    <div class="tournament-details">
        <h2><?php echo htmlspecialchars($selectedTournament['nomTournois']); ?></h2>
        <p class="tournament-location">
            <strong>Ville:</strong> <?php echo htmlspecialchars($selectedTournament['villeTournois']); ?>
        </p>
        
        <?php foreach ($matches as $phase => $phaseMatches): ?>
            <?php if (!empty($phaseMatches)): ?>
                <div class="phase-section">
                    <h3><?php echo $phase; ?></h3>
                    <div class="matches-container">
                        <?php foreach ($phaseMatches as $match): ?>
                            <div class="match-card">
                                <div class="teams">
                                    <span class="team team1"><?php echo htmlspecialchars($match['equipe1'] ?? 'Équipe non définie'); ?></span>
                                    <span class="vs">VS</span>
                                    <span class="team team2"><?php echo htmlspecialchars($match['equipe2'] ?? 'Équipe non définie'); ?></span>
                                </div>
                                <div class="score">
                                    <?php if (isset($match['scoreEquipe1']) && isset($match['scoreEquipe2'])): ?>
                                        <?php echo $match['scoreEquipe1']; ?> - <?php echo $match['scoreEquipe2']; ?>
                                    <?php else: ?>
                                        Match à venir
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($match['winnerName'])): ?>
                                    <div class="winner">
                                        <strong>Gagnant:</strong> <?php echo htmlspecialchars($match['winnerName']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <?php /* Commentaire début de la section statistiques
        <?php if (!empty($statistics)): ?>
            <div class="statistics-section">
                <h3>Statistiques des Équipes</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Équipe</th>
                            <th>Victoires</th>
                            <th>Défaites</th>
                            <th>Buts Marqués</th>
                            <th>Buts Encaissés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistics as $stat): ?>
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
            </div>
            
            <div class="export-section">
                <a href="index.php?controller=Tournament&action=exportPdf&tournoi=<?php echo $selectedTournament['idTournois']; ?>" class="btn btn-export">
                    Exporter en PDF
                </a>
            </div>
        <?php endif; ?>
        Commentaire fin de la section statistiques */ ?>
    </div>
<?php else: ?>
    <div class="no-tournament-selected">
        <p>Sélectionnez un tournoi pour afficher ses détails.</p>
    </div>
<?php endif; ?>

<a href="index.php?controller=User&action=dashboard" class="btn">Retour à l'accueil</a>

<style>
h1, h2, h3 {
    text-align: center;
}

.select-container {
    background-color: #0077cc;
    padding: 20px;
    border-radius: 10px;
    width: 300px;
    margin: 20px auto;
    text-align: center;
    color: white;
}

select {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

select:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(0, 119, 204, 0.8);
}

.tournament-details {
    margin-top: 30px;
}

.tournament-location {
    text-align: center;
    font-size: 18px;
    margin-bottom: 30px;
}

.phase-section {
    margin-bottom: 40px;
}

.matches-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.match-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}

.teams {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.team {
    flex: 1;
    font-weight: bold;
    padding: 8px;
}

.vs {
    padding: 0 10px;
    color: #777;
}

.score {
    font-size: 24px;
    font-weight: bold;
    background-color: #f5f5f5;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
}

.winner {
    color: #28a745;
    font-weight: bold;
}

.statistics-section {
    margin-top: 50px;
}

.export-section {
    text-align: center;
    margin: 30px 0;
}

.btn-export {
    background-color: #28a745;
}

.btn-export:hover {
    background-color: #218838;
}

.no-tournament-selected {
    text-align: center;
    padding: 30px;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin: 30px 0;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
    