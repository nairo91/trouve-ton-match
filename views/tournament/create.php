<?php
$pageTitle = "Créer un tournoi - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Créer un tournoi</h1>

<form method="POST" action="index.php?controller=Tournament&action=create">
    <div class="form-group">
        <label for="nomTournoi">Nom du tournoi :</label>
        <input type="text" name="nomTournoi" id="nomTournoi" required>
    </div>

    <div class="form-group">
        <label for="villeTournoi">Ville :</label>
        <input type="text" name="villeTournoi" id="villeTournoi" required>
    </div>

    <h3>Choisissez 8 équipes pour le tournoi :</h3>
    <div class="teams-selection">
        <?php foreach ($teams as $team): ?>
            <div class="team-card" onclick="toggleSelection(this)">
                <input type="checkbox" name="equipes[]" value="<?php echo $team['idEquipe']; ?>" class="team-checkbox">
                <span><?php echo htmlspecialchars($team['nomEquipe']); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="selection-counter">
        <span id="selected-count">0</span>/8 équipes sélectionnées
    </div>

    <div class="form-group">
        <input type="submit" value="Créer le tournoi" class="btn">
    </div>
</form>

<a href="index.php?controller=User&action=dashboard" class="btn btn-secondary">Retour à l'accueil</a>

<script>
function toggleSelection(card) {
    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    card.classList.toggle('selected', checkbox.checked);
    updateCounter();
}

function updateCounter() {
    const checkboxes = document.querySelectorAll('.team-checkbox:checked');
    const counter = document.getElementById('selected-count');
    counter.textContent = checkboxes.length;
    
    // Change counter color based on count
    if (checkboxes.length === 8) {
        counter.style.color = 'green';
    } else if (checkboxes.length > 8) {
        counter.style.color = 'red';
    } else {
        counter.style.color = '#333';
    }
}
</script>

<style>
h1, h3 {
    text-align: center;
}

form {
    max-width: 800px;
}

.teams-selection {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.team-card {
    background-color: #e0f7fa;
    border: 2px solid transparent;
    padding: 15px;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s, border-color 0.2s;
    position: relative;
    text-align: center;
}

.team-card:hover {
    transform: translateY(-3px);
    border-color: #00aaff;
}

.team-card.selected {
    background-color: #00aaff;
    color: white;
    border-color: #0077cc;
}

.team-checkbox {
    display: none;
}

.selection-counter {
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
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
