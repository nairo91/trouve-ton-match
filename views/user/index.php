<?php
$pageTitle = "Accueil - Trouve Ton Match";
// Ne pas inclure le header commun pour cette page spécifique
// require_once 'views/layout/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        /* Style général */
        body {
            font-family: Arial, sans-serif;
            background-image: url('assets/images/background-football.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            margin: 0;
            padding: 0;
        }

        /* Header */
        header {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 35px 10px;
            text-align: center;
            position: relative;
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        /* Logo */
        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 80px;
            height: auto;
        }

        /* Navigation */
        nav {
            background: linear-gradient(to right, #005fa3, #004080);
            display: flex;
            justify-content: center;
            padding: 15px 0;
        }

        nav a {
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 1.2em;
            transition: background 0.3s ease-in-out;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }

        /* Section Hero */
        .hero {
            text-align: center;
            background: linear-gradient(to right, #0077cc, #005fa3);
            padding: 80px 20px;
        }

        .hero h2 {
            font-size: 3em;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.5em;
        }

        /* Contenu (3 blocs) */
        .content {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 50px 20px;
            background: #dfefff; /* Bleu très clair */
            flex-wrap: wrap;
        }

        .content div {
            width: 30%;
            padding: 30px;
            background: #f0faff; /* Blanc légèrement teinté bleu */
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease-in-out, background 0.3s ease-in-out;
            border: 2px solid #0077cc;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .content div:hover {
            background: #e3f2fd; /* Bleu plus marqué au survol */
            transform: scale(1.05);
        }

        /* Titres des blocs */
        .content div h3 {
            font-size: 2em;
            color: #005fa3;
            margin-bottom: 10px;
        }

        /* Texte des blocs */
        .content div p {
            font-size: 1.2em;
            color: #333;
        }

        /* Responsive */
        @media screen and (max-width: 900px) {
            .content {
                flex-direction: column;
                align-items: center;
            }

            .content div {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="assets/images/logo.png" alt="Logo" class="logo">
        <h1>Gestion de Matchs de Football</h1>
    </header>

    <nav>
        <a href="index.php?controller=User&action=login">Connexion</a>
        <a href="index.php?controller=User&action=register">Inscription</a>
    </nav>

    <div class="hero">
        <h2>Bienvenue sur la plateforme de gestion de matchs de football</h2>
        <p>Organisez vos matchs, gérez vos équipes, participez à des tournois et bien plus encore.</p>
    </div>

    <div class="content">
        <div>
            <h3>Créez et Rejoignez des Matchs</h3>
            <p>Participez à des matchs de football autour de vous, que ce soit pour le plaisir ou pour la compétition.</p>
        </div>
        <div>
            <h3>Gérez vos Équipes</h3>
            <p>Formez une équipe avec vos amis, recrutez de nouveaux membres et affrontez d'autres équipes.</p>
        </div>
        <div>
            <h3>Participez à des Tournois</h3>
            <p>Inscrivez votre équipe à des tournois locaux et montrez vos talents sur le terrain.</p>
        </div>
    </div>
</body>
</html>
<?php /* On n'inclue pas le footer pour cette page spécifique */ ?>
