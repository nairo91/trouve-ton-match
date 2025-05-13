<?php
$pageTitle = "Créer un match - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Créer un Match</h1>

<?php if (!empty($message)): ?>
    <div class="message <?php echo $messageClass; ?>"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST" action="index.php?controller=Match&action=create">
    <div class="form-group">
        <label for="nom_match">Nom du Match:</label>
        <input type="text" name="nom_match" id="nom_match" required>
    </div>

    <div class="form-group">
        <label for="type_match">Type de Match:</label>
        <select name="type_match" id="type_match" required>
            <option value="">Choisissez un type</option>
            <option value="Amical">Amical</option>
            <option value="Compétition">Compétition</option>
            <option value="Tournoi">Tournoi</option>
            <option value="Exhibition">Exhibition</option>
        </select>
    </div>

    <div class="form-group">
        <label for="nombre_joueurs_recherches">Nombre de Joueurs Recherchés:</label>
        <input type="number" name="nombre_joueurs_recherches" id="nombre_joueurs_recherches" min="1" required>
    </div>

    <div class="form-group">
        <label for="ville">Ville:</label>
        <input type="text" name="ville" id="ville" required>
    </div>

    <div class="form-group">
        <input type="submit" value="Créer le match" class="btn">
    </div>
</form>

<a href="index.php?controller=User&action=dashboard" class="btn btn-secondary">Retour à l'accueil</a>

<style>
h1 {
    text-align: center;
    margin-bottom: 30px;
}

form {
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn {
    background-color: #00aaff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: inline-block;
    text-decoration: none;
}

.btn:hover {
    background-color: #0088cc;
}

.btn-secondary {
    background-color: #6c757d;
    display: inline-block;
    margin-top: 20px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.message {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
