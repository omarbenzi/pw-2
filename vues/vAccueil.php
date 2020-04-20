<?php $this->titre = "Page d'accueil"; ?>

<div class="main">
    <div class="left-side">
        <h2>CATÉGORIE</h2>
        <div id="accordion">

            <?php foreach ($categories as $categorie => $details) : ?>

                <h4><?= $categorie ?></h4>
                <div>
                    <ul>
                        <?php foreach ($details['sousCategorie'] as $sousCategorie) : ?>
                            <li><a href="http://localhost/pw2/pw-2/annonce?item=annonce&action=getbySCategory&id=<?= $details[$sousCategorie]['id'] ?>"><?= $sousCategorie ?></a></li>
                        <?php endforeach;
                        ?>
                    </ul>
                </div>
            <?php endforeach;
            ?>

        </div>
    </div>

    <div class="right-side">

        <?php foreach ($annonces as $annonce) : ?>
            <article class="produit">
                <h4 class="nom"><?= $annonce['titre'] ?></h4>
                <p class="image">
                    <img src="images/logo.png">
                </p>
                <p class="description"><?= $annonce['description'] ?></p>
                <p class="prix"><span><?= $annonce['prix'] ?></span></p>
                <?php if (isset($_SESSION['id'])) : ?>
                    <p class="ajouterAuFavoris" id="<?= $annonce['idarticle'] ?>"><span>Ajouter au favoris+</span></p>
                <?php endif ?>
                <div class="date">
                    <p>Date: <span><?= $annonce['datePublication'] ?></span></p>
                </div>

            </article>

        <?php endforeach;
        ?>


    </div>
</div>