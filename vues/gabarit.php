<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo str_replace("\\", "", Controleur::$base_uri) ?>/styles/normalize.css">
    <link rel="stylesheet" href="<?php echo str_replace("\\", "", Controleur::$base_uri) ?>/styles/basic-style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap" rel="stylesheet"> <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <title><?php echo $titre ?></title>
</head>

<body>
    <div id="global">
        <header>
            <div class="logo">
                <a href="index.html"><img src="<?php echo str_replace("\\", "", Controleur::$base_uri) ?>images/logo.png" alt="logo" width="80px" height="50px"></a>
            </div>
            <div class="search-box">
                <input class="search-txt" type="text" name="search" placeholder="Type to search">
                <a class="search-btn" href="#"><i class="fas fa-search"></i></a>
            </div>
            <nav>
                <ul class="srt-menu">
                    <li id="home"><a href="annonce" class="homeIcon">Home</a></li>
                    <li><a href="#">Messages</a></li>
                    <li> <?= (isset($_SESSION['id'])) ? '<a href="user?action=deconnecter">Déconnexion</a>' : '<a href="user?item=user&action=connexion">Connexion</a>'   ?>

                    </li>
                    <?= (isset($_SESSION['id'])) ? '
                    <li><a href="annonce?action=get&item=favoris&id=' .  $_SESSION['id']  . '">favoris</a></li>'  : '' ?>

                    <li><a href="#">Contact</a></li>
                    kkkkkkkkkkkkk
                </ul> <!-- .srt-menu -->
            </nav> <!-- nav -->
        </header>
        <div id="contenu">
            <?php echo $contenu ?>
            <!-- contenu d'une vue spécifique -->
        </div>

        <script type="text/javascript" src="<?php echo str_replace("\\", "", Controleur::$base_uri) ?>js/main.js"></script>
        <footer>
            <div class="links_container">
                <div class="links">
                    <h3>Nous joindre</h3>
                    <nav>
                        <a href="#">Un Numéro Unique:455</a>
                        <a href="#">Par Courriel</a>
                        <a href="#">En Personne</a>
                    </nav>
                </div>
                <div class="links">
                    <h3>Services</h3>
                    <nav>
                        <a href="#">Gestion de compte</a>
                        <a href="#">Programme d'échange</a>
                        <a href="#">Centre d'assistance</a>
                    </nav>
                </div>
                <div class="links">
                    <h3>Suivez-Nous</h3>
                    <nav>
                        <a href="https://www.facebook.com/"><img src="images/Facebook.png" alt="Facebook"> <span>Facebook</span></a>
                        <a href="https://twitter.com"><img src="images/twitter.png" alt="Twitter"><span>Twitter</span></a>
                        <a href="https://www.instagram.com/"><img src="images/Instagram.png" alt="Instagram"><span>Instagram</span></a>
                    </nav>
                </div>
            </div>
            <div class="brand">
                <p> © 2020 NOM DU SITE Tous droits réservés.</p>
                <p>L'utilisation de ce site est conditionelle à l'acceptation des conditions d'utilisation et de la politique de confidentialité </p>
            </div>
        </footer>
    </div>
</body>

</html>