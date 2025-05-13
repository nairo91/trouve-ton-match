<?php
$pageTitle = "Mes statistiques - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Statistiques de <?php echo htmlspecialchars($pseudo); ?></h1>



<?php if (empty($matches)): ?>
    <div class="alert alert-info">
        Vous ne participez à aucun match actuellement. Aucune statistique disponible.
    </div>
<?php else: ?>
    <div class="stats-summary">
        <h2>Résumé des Statistiques</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom du Match</th>
                    <th>Type de Match</th>
                    <th>Ville</th>
                    <th>Buts</th>
                    <th>Passes Décisives</th>
                    <th>Arrêts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matches as $match): ?>
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

    <h2>Mettre à jour vos statistiques</h2>
    
    <?php foreach ($matches as $match): ?>
        <div class="stats-form">
            <h3><?php echo htmlspecialchars($match['nomMatch'] ?? 'Match sans nom'); ?></h3>
            
            <form method="post" action="index.php?controller=Match&action=statistics">
                <input type="hidden" name="id_match" value="<?php echo $match['idMatch']; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Buts:</label>
                        <input type="number" name="nombre_buts" value="<?php echo $match['nombre_buts'] ?? 0; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Passes décisives:</label>
                        <input type="number" name="nombre_passes_decisives" value="<?php echo $match['nombre_passes_decisives'] ?? 0; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Arrêts:</label>
                        <input type="number" name="nombre_arrets" value="<?php echo $match['nombre_arrets'] ?? 0; ?>" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="submit" value="Mettre à jour" class="btn">
                </div>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<a href="index.php?controller=User&action=dashboard" class="btn btn-secondary">Retour à l'accueil</a>

<style>
h1, h2 {
    text-align: center;
    margin-bottom: 30px;
}

.stats-summary {
    margin-bottom: 40px;
}

.stats-form {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.stats-form h3 {
    color: #00aaff;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}

.form-row .form-group {
    flex: 1;
    min-width: 150px;
}

.btn-secondary {
    background-color: #6c757d;
    display: inline-block;
    margin-top: 20px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
