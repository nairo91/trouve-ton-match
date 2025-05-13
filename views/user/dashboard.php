<?php
$pageTitle = "Tableau de bord - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Bienvenue, <?php echo Session::get('pseudo'); ?> !</h1>



<div class="video-section">
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/Yq7Yy8H5J-8?autoplay=1&mute=1&controls=1" 
                title="YouTube video player" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" 
                allowfullscreen>
        </iframe>
    </div>
</div>

<!--
<div class="dashboard-container">
    <div class="dashboard-card">
        <h3>Matchs</h3>
        <p>Créez ou rejoignez des matchs de football dans votre région.</p>
        <div class="actions">
            <a href="index.php?controller=Match&action=create" class="btn">Créer un match</a>
            <a href="index.php?controller=Match&action=join" class="btn">Rejoindre un match</a>
            <a href="index.php?controller=Match&action=myMatches" class="btn">Mes matchs</a>
        </div>
    </div>
    
    <div class="dashboard-card">
        <h3>Équipes</h3>
        <p>Gérez vos équipes ou rejoignez de nouvelles équipes existantes.</p>
        <div class="actions">
            <a href="index.php?controller=Team&action=create" class="btn">Créer une équipe</a>
            <a href="index.php?controller=Team&action=join" class="btn">Rejoindre une équipe</a>
            <a href="index.php?controller=Team&action=myTeams" class="btn">Mes équipes</a>
        </div>
    </div>
    
    <div class="dashboard-card">
        <h3>Tournois</h3>
        <p>Participez à des tournois ou suivez les résultats des tournois en cours.</p>
        <div class="actions">
            <a href="index.php?controller=Tournament&action=show" class="btn">Voir les tournois</a>
            <?php if (Session::hasRole('admin')): ?>
                <a href="index.php?controller=Tournament&action=create" class="btn">Créer un tournoi</a>
                <a href="index.php?controller=Tournament&action=manage" class="btn">Gérer les tournois</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-card">
        <h3>Statistiques</h3>
        <p>Consultez vos statistiques personnelles et suivez vos performances.</p>
        <div class="actions">
            <a href="index.php?controller=Match&action=statistics" class="btn">Mes statistiques</a>
            <?php if (Session::hasRole('analyste')): ?>
                <a href="index.php?controller=Statistics&action=dashboard" class="btn">Tableau de bord statistique</a>
            <?php endif; ?>
        </div>
    </div> -->
</div>

<style>

    /* Styles pour la section vidéo */
.video-section {
    max-width: 800px; /* Limiter la largeur pour que la vidéo ne soit pas trop large */
    margin: 0 auto 30px; /* Centrer horizontalement et ajouter une marge en bas */
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    padding: 20px;
}

.video-container {
    position: relative;
    padding-bottom: 56.25%; /* Ratio d'aspect 16:9 pour la vidéo */
    height: 0;
    overflow: hidden;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 5px;
}
h1 {
    text-align: center;
    margin-bottom: 30px;
}

.dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.dashboard-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);   
    padding: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.dashboard-card h3 {
    color: #00aaff;
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.dashboard-card p {
    color: #666;
    margin-bottom: 20px;
}

.actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    background-color: #00aaff;
    color: white;
    text-align: center;
    padding: 10px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0088cc;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
