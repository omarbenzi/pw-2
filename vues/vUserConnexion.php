<?php $this->titre = "Connexion"; ?>



<body class="body-connection">
    <img class="wave" src="images/wave.png">
    <div class="container">
        <div class="img">
            <img src="images/bg.svg">
        </div>
        <div class="login-content">
            <form action="index.html">
                <img src="images/avatar.svg">
                <h2 class="title">Bienvenue</h2>
                <p class="erreur">&nbsp;<?= (isset($msgErreur)) ? $msgErreur : null ?></p>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Username</h5>
                        <input type="text" class="input" value="<?= (isset($admin['identifiant'])) ? $admin['identifiant'] : null ?>">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" class="input" <?= (isset($admin['mdp'])) ? $admin['mdp'] : null ?>>
                    </div>
                </div>

                <input type="submit" class="btn" value="Connexion">
                <input type="button" class="btn inscription" value="S'inscrire">
            </form>
        </div>
    </div>

    <form id="myForm" action="user" method="post">
        <input type="hidden" name="item" value="user">
        <input type="hidden" name="action" value="ajouter">
    </form>
    <script type="text/javascript" src="js/main.js"></script>
    <script>
        $('.inscription').click('click', function(params) {

            $('#myForm').submit();

        })
    </script>

</body>


</div>
<footer>
</footer>
</div>
</body>

</html>