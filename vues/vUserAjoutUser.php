<?php $this->titre = "Ajouter un auteur"; ?>


<body class="body-connection">
    <p class="erreur">&nbsp; <?= (isset($erreurMysql)) ? $erreurMysql : null ?></p>

    <img class="wave" src="images/wave.png">
    <div class="container">
        <div class="img">
            <img src="images/security.svg" width="300px" height="300px">
        </div>
        <div class="login-content">
            <form action="user?" method="post">
                <img src="images/avatar.svg">
                <h2 class="title">Inscription</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Nom </h5>
                        <input type="text" name="nom" class="input" value="<?= (isset($user['nom'])) ? $user['nom'] : null ?>">

                    </div>
                </div>
                <p class="erreur">&nbsp; <?= (isset($erreursHydrate['nom'])) ? $erreursHydrate['nom'] : null ?></p>

                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-at"></i>
                    </div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="text" name="email" class="input" value="<?= (isset($user['email'])) ? $user['email'] : null ?>">

                    </div>
                </div>
                <p class="erreur">&nbsp; <?= (isset($erreursHydrate['email'])) ? $erreursHydrate['email'] : null ?></p>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Mot de passe</h5>
                        <input type="password" name="password" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Confirmation de mot de passe</h5>
                        <input type="password" class="input">
                    </div>
                </div>
                <input type="hidden" name="item" value="user">
                <input type="hidden" name="action" value="ajouter">
                <input type="hidden" name="envoyer" value="1">
                <input type="submit" class="btn" value="S'inscrire">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>