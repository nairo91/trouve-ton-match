<?php
$pageTitle = "Administration - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<h1>Tableau de bord Administrateur</h1>

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

<div class="admin-dashboard">
    <div class="admin-card">
        <h3>Gestion des utilisateurs</h3>
        <p>Gérez les rôles des utilisateurs et créez de nouveaux types de rôles.</p>
        <a href="index.php?controller=User&action=manageRoles" class="btn">Gérer les rôles</a>
    </div>
    
    <div class="admin-card">
        <h3>Gestion des tournois</h3>
        <p>Créez, modifiez et gérez les tournois de l'application.</p>
        <a href="index.php?controller=Tournament&action=create" class="btn">Créer un tournoi</a>
        <a href="index.php?controller=Tournament&action=manage" class="btn">Gérer les tournois</a>
    </div>
    
    <div class="admin-card">
        <h3>Statistiques</h3>
        <p>Consultez les statistiques globales de l'application.</p>
        <a href="index.php?controller=Statistics&action=dashboard" class="btn">Voir les statistiques</a>
    </div>
    
    <div class="admin-card">
        <h3>Navigation</h3>
        <p>Accédez aux différentes sections de l'application.</p>
        <a href="index.php?controller=User&action=dashboard" class="btn">Tableau de bord utilisateur</a>
        <a href="index.php?controller=Tournament&action=show" class="btn">Voir les tournois</a>
        <a href="index.php?controller=Match&action=myMatches" class="btn">Mes matchs</a>
    </div>
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

.admin-dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.admin-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 25px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.admin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

.admin-card h3 {
    color: #0077cc;
    margin-top: 0;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.admin-card p {
    color: #555;
    margin-bottom: 20px;
    min-height: 50px;
}

.admin-card .btn {
    display: block;
    margin-bottom: 10px;
    text-align: center;
}

.admin-card .btn:last-child {
    margin-bottom: 0;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
