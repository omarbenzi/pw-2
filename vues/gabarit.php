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
                    <li id="home"><a href="#home" class="homeIcon">Home</a></li>
                    <li><a href="#">Messages</a></li>
                    <li><a href="#">Connection</a></li>
                    <li><a href="#">favoris</a></li>
                    <li><a href="#">Contact</a></li>
                </ul> <!-- .srt-menu -->
            </nav> <!-- nav -->
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