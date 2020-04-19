<?php $this->titre = "Ajouter un auteur"; ?>
<section id="ajout-modification">

    <h1>Ajouter un auteur</h1>

    <p class="erreur">&nbsp <?= (isset($erreurMysql)) ? $erreurMysql : null ?></p>

    <form method="POST" action="admin?item=auteur&action=ajouter&id=">
        <label>Nom</label>
        <input name="nom" value="<?= (isset($auteur['nom'])) ? $auteur['nom'] : null ?>">
        <p class="erreur">&nbsp; <?= (isset($erreursHydrate['nom'])) ? $erreursHydrate['nom'] : null ?></p>

        <label>Prénom</label>
        <input name="prenom" value="<?= (isset($auteur['prenom'])) ? $auteur['prenom'] : null ?>">
        <p class="erreur">&nbsp; <?= (isset($erreursHydrate['prenom'])) ? $erreursHydrate['prenom'] : null ?></p>
        <input type="submit" name="Envoyer" value="Envoyer">
    </form>
</section>

<body class="body-connection">
    <img class="wave" src="images/wave.png">
    <div class="container">
        <div class="img">
            <img src="images/security.svg" width="300px" height="300px">
        </div>
        <div class="login-content">
            <form action="user?action=ajouter&item=user">
                <img src="images/avatar.svg">
                <h2 class="title">Sign In</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Nom </h5>
                        <input type="text" name="nom" class="input" value="<?= (isset($user['nom'])) ? $user['nom'] : null ?>">
                    </div>
                </div>

                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-at"></i>
                    </div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="text" name="email" class="input" value="<?= (isset($user['email'])) ? $user['email'] : null ?>">
                    </div>
                </div>
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
                <input type="submit" class="btn" value="Sign In">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>