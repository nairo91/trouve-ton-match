<?php
$pageTitle = "Gérer les tournois - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Gérer les Tournois</h1>

<div class="select-container">
    <form method="GET" action="index.php">
        <input type="hidden" name="controller" value="Tournament">
        <input type="hidden" name="action" value="manage">
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

<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if ($selectedTournament): ?>
    <div class="tournament-details">
        <h2><?php echo htmlspecialchars($selectedTournament['nomTournois']); ?></h2>
        <p class="tournament-location">
            <strong>Ville:</strong> <?php echo htmlspecialchars($selectedTournament['villeTournois']); ?>
        </p>
        
        <?php foreach ($phases as $phase): ?>
            <div class="phase-section">
                <h3><?php echo $phase; ?></h3>
                
                <?php if (empty($matchesByPhase[$phase])): ?>
                    <p class="no-matches">Aucun match pour cette phase.</p>
                <?php else: ?>
                    <div class="matches-container">
                        <?php foreach ($matchesByPhase[$phase] as $match): ?>
                            <div class="match-card">
                                <div class="teams">
                                    <span class="team team1"><?php echo htmlspecialchars($match['equipe1'] ?? 'Équipe non définie'); ?></span>
                                    <span class="vs">VS</span>
                                    <span class="team team2"><?php echo htmlspecialchars($match['equipe2'] ?? 'Équipe non définie'); ?></span>
                                </div>
                                
                                <form method="POST" action="index.php?controller=Tournament&action=manage&tournoi=<?php echo $selectedTournament['idTournois']; ?>">
                                    <input type="hidden" name="idMatch" value="<?php echo $match['idMatch']; ?>">
                                    
                                    <div class="score-input">
                                        <label>Score:</label>
                                        <div class="score-fields">
                                            <input type="number" name="scoreEquipe1" value="<?php echo $match['scoreEquipe1'] ?? 0; ?>" min="0" class="score-field">
                                            <span>-</span>
                                            <input type="number" name="scoreEquipe2" value="<?php echo $match['scoreEquipe2'] ?? 0; ?>" min="0" class="score-field">
                                        </div>
                                    </div>
                                    
                                    <div class="winner-select">
                                        <label>Gagnant:</label>
                                        <select name="gagnant">
                                            <option value="<?php echo $match['idEquipe1']; ?>" <?php echo ($match['winner'] == $match['idEquipe1']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($match['equipe1']); ?>
                                            </option>
                                            <option value="<?php echo $match['idEquipe2']; ?>" <?php echo ($match['winner'] == $match['idEquipe2']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($match['equipe2']); ?>
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn update-btn">Mettre à jour</button>
                                </form>
                                
                                <?php if (!empty($match['winnerName'])): ?>
                                    <div class="winner-display">
                                        <strong>Gagnant actuel:</strong> <?php echo htmlspecialchars($match['winnerName']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-tournament-selected">
        <p>Sélectionnez un tournoi pour le gérer.</p>
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

.alert {
    padding: 15px;
    margin: 20px 0;
    border-radius: 5px;
    text-align: center;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
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

.no-matches {
    text-align: center;
    font-style: italic;
    color: #777;
    padding: 20px;
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
}

.teams {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.team {
    flex: 1;
    font-weight: bold;
    padding: 8px;
    text-align: center;
}

.vs {
    padding: 0 10px;
    color: #777;
}

.score-input, .winner-select {
    margin-bottom: 15px;
}

.score-fields {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
}

.score-field {
    width: 60px;
    text-align: center;
    padding: 8px;
    font-size: 18px;
    font-weight: bold;
}

.update-btn {
    width: 100%;
    margin-top: 10px;
    background-color: #0077cc;
}

.update-btn:hover {
    background-color: #005fa3;
}

.winner-display {
    margin-top: 15px;
    text-align: center;
    padding: 10px;
    background-color: #e8f5e9;
    border-radius: 5px;
    color: #2e7d32;
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
