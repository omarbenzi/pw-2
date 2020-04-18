<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo str_replace("\\", "", Controleur::$base_uri) ?>/styles/basic-style.css">
    <link rel="stylesheet" href="<?php echo str_replace("\\", "", Controleur::$base_uri) ?>/styles/normalize.css">
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
            <h1>Bibliothèque</h1>
            <ul>
                <li><a class="<?php echo $this->vue === "Accueil" ? "active" : ""; ?>" href=".">Accueil</a></li>
                <li><a class="<?= ($this->vue === "Livres" || $this->vue === "LivresTri") ? "active" : ""; ?>" href="livres">Liste des livres</a></li>
                <li><a class="<?php echo $this->vue === "LivresRecherche" ? "active" : ""; ?>" href="livres?action=recherche">Recherche</a></li>
            </ul>
        </header>
        <div id="contenu">
            <?php echo $contenu ?>
            <!-- contenu d'une vue spécifique -->
        </div>
        <footer>
            <script type="text/javascript" src="<?php echo str_replace("\\", "", Controleur::$base_uri) ?>js/main.js"></script>
        </footer>
    </div>
</body>

</html>