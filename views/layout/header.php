<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Trouve Ton Match'; ?></title>
    <?php if ($pageTitle !== "Accueil - Trouve Ton Match"): // Styles pour les pages autres que l'accueil ?>
    <style>
        /* Style général */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            background-image: url('assets/images/football_field_background.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .header {
            background-color: rgb(50 53 50 / 90%);
            padding: 20px;
            text-align: center;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 3em;
            text-shadow: 2px 2px 4px #000;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 80px;
            height: auto;
        }

        .nav {
            margin: 20px 0;
            text-align: center;
        }

        .nav a, .nav .dropbtn {
            background-color: #00aaff;
            color: white;
            padding: 15px 25px;
            margin: 10px;
            text-decoration: none;
            display: inline-block;
            border-radius: 10px;
            font-size: 1.2em;
            transition: background-color 0.3s, transform 0.3s;
        }

        .nav a:hover, .nav .dropbtn:hover {
            background-color: #00aaff61;
            transform: scale(1.1);
        }

        /* Menu déroulant */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #00aaff;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #00aaff61;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
            margin-bottom: 100px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: rgb(50 53 50 / 90%);
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Classes pour les messages */
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Formulaires */
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        
        button, input[type="submit"] {
            background-color: #00aaff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        button:hover, input[type="submit"]:hover {
            background-color: #0088cc;
        }
        
        /* Tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #00aaff;
            color: white;
            text-align: center;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #e0f7fa;
        }
    </style>
    <?php endif; ?>
</head>
<body>
    <div class="header">
        <h1>Bienvenue sur le site de Trouve Ton Match</h1>
        <img src="assets/images/logo.png" alt="Logo" class="logo">
    </div>

    <div class="nav">
        <?php if (Session::isLoggedIn()): ?>
            <a href="index.php?controller=User&action=dashboard">Accueil</a>
            
            <div class="dropdown">
                <button class="dropbtn">Match</button>
                <div class="dropdown-content">
                    <a href="index.php?controller=Match&action=create">Créer un match</a>
                    <a href="index.php?controller=Match&action=join">Rejoindre un match</a>
                    <a href="index.php?controller=Match&action=myMatches">Mes matchs</a>
                    <a href="index.php?controller=Match&action=statistics">Mes statistiques</a>
                </div>
            </div>
            
            <div class="dropdown">
                <button class="dropbtn">Equipe</button>
                <div class="dropdown-content">
                    <a href="index.php?controller=Team&action=create">Créer une équipe</a>
                    <a href="index.php?controller=Team&action=join">Rejoindre une équipe</a>
                    <a href="index.php?controller=Team&action=myTeams">Mes équipes</a>
                </div>
            </div>
            
            <div class="dropdown">
                <button class="dropbtn">Tournoi</button>
                <div class="dropdown-content">
                    <?php if (Session::hasRole('admin')): ?>
                        <a href="index.php?controller=Tournament&action=create">Créer un tournoi</a>
                        <a href="index.php?controller=Tournament&action=manage">Gérer les tournois</a>
                    <?php endif; ?>
                    <a href="index.php?controller=Tournament&action=show">Voir les tournois</a>
                </div>
            </div>
            
            <?php if (Session::hasRole('analyste')): ?>
                <a href="index.php?controller=Statistics&action=dashboard">Tableau de bord</a>
            <?php endif; ?>
            
            <?php if (Session::hasRole('admin')): ?>
                <a href="index.php?controller=User&action=manageRoles">Gérer les rôles</a>
            <?php endif; ?>
            
            <a href="index.php?controller=User&action=logout">Se déconnecter</a>
        <?php else: ?>
            <?php if ($pageTitle === "Accueil - Trouve Ton Match"): // Pour la page d'accueil, on affiche juste les liens de base ?>
                <a href="index.php?controller=User&action=login">Connexion</a>
                <a href="index.php?controller=User&action=register">Inscription</a>
            <?php else: // Pour les autres pages ?>
                <a href="index.php">Accueil</a>
                <a href="index.php?controller=User&action=login">Connexion</a>
                <a href="index.php?controller=User&action=register">Inscription</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if ($pageTitle !== "Accueil - Trouve Ton Match"): // Container pour toutes les pages sauf l'accueil ?>
    <div class="container">
        <?php if (isset($message) && !empty($message)): ?>
            <div class="message <?php echo isset($messageClass) ? $messageClass : ''; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
