<?php $this->titre = "Page d'accueil"; ?>

<div class="main">
    <div class="left-side">
        <h2>CATÉGORIE</h2>
        <div id="accordion">

            <?php foreach ($categories as $categorie => $details) : ?>

                <h4><?= $categorie ?></h4>
                <div>
                    <ul>
                        <li><a href="#">Accessoires, Pneus</a></li>
                        <li><a href="#">Camions</a></li>
                        <li><a href="#">Pièces</a></li>
                        <li><a href="#">Motos</a></li>
                    </ul>
                </div>
            <?php endforeach;
            ?>

        </div>
    </div>

    <div class="right-side">

        <?php foreach ($annoncesSponsoises as $annoncesSponsoise) : ?>
            <article class="produit">
                <h4 class="nom"><?= $annoncesSponsoise['titre'] ?></h4>
                <p class="image">
                    <img src="images/logo.png">
                </p>
                <p class="description"><?= $annoncesSponsoise['description'] ?></p>
                <p class="prix"><span><?= $annoncesSponsoise['prix'] ?></span></p>
                <div class="date">
                    <p>Date: <span><?= $annoncesSponsoise['datePublication'] ?></span></p>
                </div>
            </article>
        <?php endforeach;
        ?>


    </div>
</div>