<?php
$pageTitle = "Gestion des rôles - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Gestion des Rôles Utilisateurs</h1>

<?php if (!empty($message)): ?>
    <div class="alert alert-info">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="roles-container">
    <div class="roles-section">
        <h2>Liste des Utilisateurs</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pseudo</th>
                        <th>Rôle actuel</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['idJoueur']; ?></td>
                            <td><?php echo htmlspecialchars($user['pseudo']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <form method="POST" action="index.php?controller=User&action=manageRoles" class="role-form">
                                    <input type="hidden" name="user_id" value="<?php echo $user['idJoueur']; ?>">
                                    <div class="form-inline">
                                        <select name="role">
                                            <?php foreach ($roles as $roleOption): ?>
                                                <option value="<?php echo $roleOption; ?>" <?php echo ($user['role'] == $roleOption) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($roleOption); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" name="update_user" class="btn">Mettre à jour</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="new-role-section">
        <h2>Créer un Nouveau Rôle</h2>
        <form method="POST" action="index.php?controller=User&action=manageRoles" class="new-role-form">
            <div class="form-group">
                <label for="new_role">Nom du nouveau rôle :</label>
                <input type="text" id="new_role" name="new_role" placeholder="Entrez le nom du nouveau rôle" required>
            </div>
            <div class="form-group">
                <button type="submit" name="create_role" class="btn">Créer le rôle</button>
            </div>
        </form>
    </div>
</div>

<div class="btn-container">
<a href="index.php?controller=User&amp;action=dashboard" class="btn">Retour à l'accueil</a>

</div>

<style>
h1, h2 {
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

.roles-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
    margin: 30px 0;
}

@media (min-width: 992px) {
    .roles-container {
        grid-template-columns: 2fr 1fr;
    }
}

.roles-section, .new-role-section {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.table-responsive {
    overflow-x: auto;
}

.role-form {
    margin: 0;
}

.form-inline {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-inline select {
    flex: 1;
    margin-bottom: 0;
}

.new-role-form {
    max-width: 100%;
}

.btn-container {
    text-align: center;
    margin-top: 20px;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
