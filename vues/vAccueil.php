<?php $this->titre = "Page d'accueil"; ?>

<div class="main">
    <div class="left-side">
        <h2>CATÉGORIE</h2>
        <div id="accordion">
            <h4>AUTOS/MOTOS</h4>
            <div>
                <ul>
                    <li><a href="#">Accessoires, Pneus</a></li>
                    <li><a href="#">Camions</a></li>
                    <li><a href="#">Pièces</a></li>
                    <li><a href="#">Motos</a></li>
                </ul>
            </div>

            <h4>MAISON</h4>
            <div>
                <ul>
                    <li><a href="#">Chambres à coucher</a></li>
                    <li><a href="#">Salons</a></li>
                    <li><a href="#">Bureaux à domicile</a></li>
                    <li><a href="#">Matelas</a></li>
                    <li><a href="#">Cuisines</a></li>
                </ul>
            </div>

            <h4>LOISIRS</h4>
            <div>
                <ul>
                    <li><a href="#">Camping et randonnée</a></li>
                    <li><a href="#">Chasse et pêche</a></li>
                    <li><a href="#">Trampolines</a></li>
                    <li><a href="#">Piscines, spas et saunas portables</a></li>
                </ul>
            </div>

            <h4>SPORTS</h4>
            <div>
                <ul>
                    <li><a href="#">Course</a></li>
                    <li><a href="#">Sport aquatiques</a></li>
                    <li><a href="#">Au gym</a></li>
                    <li><a href="#">Sports collectifs</a></li>
                    <li><a href="#">Tennis</a></li>
                    <li><a href="#">Hockey et patinage</a></li>
                </ul>
            </div>

            <h4>ÉLECTRONIQUES</h4>
            <div>
                <ul>
                    <li><a href="#">Ordinateurs et portables</a></li>
                    <li><a href="#">Serveurs et stations de travail</a></li>
                    <li><a href="#">Imprimantes et scanneurs</a></li>
                    <li><a href="#">Écrans</a></li>
                    <li><a href="#">Téléphones portables</a></li>
                    <li><a href="#">Accessoires de gaming</a></li>
                    <li><a href="#">Drônes</a></li>
                    <li><a href="#">Divers</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="right-side">

        <?php foreach ($annoncesSponsoises as $annoncesSponsoise) : // variable $livres provenant de la fonction extract($donnees) 
        ?>
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