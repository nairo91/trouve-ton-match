<?php
$pageTitle = "Inscription - Trouve Ton Match";
require_once 'views/layout/header.php';
?>

<div class="container">
    <h1>Inscription</h1>

    <?php if (!empty($errorMessage)) : ?>
        <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?controller=User&action=register">
        <div class="form-group">
            <input type="text" id="nom" name="nom" placeholder="Nom" required>
        </div>

        <div class="form-group">
            <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
        </div>

        <div class="form-group">
            <input type="number" id="age" name="age" placeholder="Âge" min="5" max="120" required>
        </div>

        <div class="form-group">
            <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" required>
        </div>

        <div class="form-group">
            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
        </div>

        <div class="form-group">
            <select id="niveau" name="niveau" required>
                <option value="" disabled selected>Niveau</option>
                <option value="Débutant">Débutant</option>
                <option value="Joueur occasionnel">Joueur occasionnel</option>
                <option value="Expert">Expert</option>
            </select>
        </div>

        <input type="submit" value="S'inscrire" class="btn-primary">
    </form>

    <a href="index.php" class="back-btn">Retour à l'accueil</a>
</div>

<style>
/* ---------- décor global ---------- */
body{
    font-family:Arial, sans-serif;
    background:#e9f5ff;            /* le bleu pâle de la capture */
    margin:0; padding:0;
}

/* ---------- carte centrale ---------- */
.container{
    max-width:500px;
    width:90%;
    margin:40px auto 60px;
    padding:40px 40px 50px;
    background:#45494c;            /* gris anthracite */
    border-radius:15px;
    box-shadow:0 4px 15px rgba(0,0,0,.3);
    text-align:center;
}

/* ---------- titre ---------- */
h1{
    color:#fff;
    margin-bottom:35px;
    text-shadow:1px 1px 2px #000;
}

/* ---------- formulaire ---------- */
form{
    display:flex;
    flex-direction:column;
    gap:18px;                      /* espace vertical régulier */
}

form .form-group{ margin:0; }

input[type="text"],
input[type="password"],
input[type="number"],
select{
    width:100%;
    padding:12px;
    font-size:16px;
    border:1px solid #ccc;
    border-radius:6px;
    box-sizing:border-box;
}
input:focus,select:focus{
    border-color:#0077cc;
    box-shadow:0 0 0 2px rgba(0,119,204,.25);
    outline:none;
}

/* ---------- bouton principal ---------- */
.btn-primary,
input[type="submit"]{
    display:inline-block;
    margin-top:10px;
    background:#003d7a;            /* bleu très sombre */
    color:#fff;
    padding:12px 24px;
    border:none;
    border-radius:5px;
    font-size:16px;
    cursor:pointer;
    transition:background .25s,transform .1s;
}
.btn-primary:hover,
input[type="submit"]:hover { background:#004e9f; }
.btn-primary:active,
input[type="submit"]:active{ transform:scale(.97); }

/* ---------- bouton secondaire ---------- */
.back-btn{
    display:inline-block;
    margin-top:25px;
    background:#0077cc;            /* bleu clair capture */
    color:#fff;
    padding:12px 30px;
    border-radius:5px;
    font-size:16px;
    font-weight:bold;
    text-decoration:none;
    transition:background .25s,transform .1s;
}
.back-btn:hover{ background:#005fa3; transform:scale(1.05);}
.back-btn:active{ background:#004080; transform:scale(.95); }

/* ---------- message d’erreur ---------- */
.error{
    color:#f44336;
    margin-bottom:15px;
    font-weight:bold;
}

/* ---------- mobile ---------- */
@media(max-width:480px){
    .container{ padding:30px 20px 40px; }
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
