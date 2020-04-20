<?php

class ControleurAnnonce
{
    private $categories = [];





    public function __construct()
    {
        try {
            $this->item   = isset($_GET['item'])   ? $_GET['item']   : "annoncesSponsorise";
            $this->action = isset($_GET['action']) ? $_GET['action'] : "get";
            $this->id     = isset($_GET['id'])     ? $_GET['id']     : "";

            if (in_array($this->item, ["annonce", "annoncesSponsorise"])) {
                if (in_array($this->action, ["get", "ajouter", "modifier", "supprimer", "getbySCategory"])) {
                    $item   = ucfirst($this->item);
                    $action = $this->action;
                    if ($action === "get" || $action === "getbySCategory") $item .= "s";
                    $methode = $action . $item;
                    // print_r($methode);
                    $this->$methode();
                    exit;
                }

                throw new exception("Action invalide");
            }
            throw new exception("Item invalide");
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }


    private function getAnnoncesSponsorises()
    {
        try {
            $reqPDO = new RequetesPDO();
            $annoncesSponsoises = $reqPDO->getAnnoncesSponsorises();
            $categories = $reqPDO->getCategories();
            $annoncesSponsoises = array_map(array($this, 'arrangeDate'), $annoncesSponsoises);
            $this->arrangeCategorie($categories);
            // print_r($this->categories);
            $vue = new Vue("Accueil", array(
                'annonces' => $annoncesSponsoises,
                'categories'   => $this->categories,
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Affiche les annonces par id Sous categorie
     *
     */
    private function getbySCategoryAnnonces()
    {
        try {
            $reqPDO = new RequetesPDO();
            $annonces = $reqPDO->getAnnoncebySousCategory($this->id);
            $categories = $reqPDO->getCategories();
            $this->arrangeCategorie($categories);
            $vue = new Vue("Accueil", array(
                'annonces' => $annonces,
                'categories'   => $this->categories,
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }



    /**
     * change le format de la date  
     *
     * @param  array $annoncesSponsoises
     *
     * @return array
     */
    private function arrangeDate($annoncesSponsoises)
    {
        $date = $annoncesSponsoises['datePublication'];

        $createDate = new DateTime($date);

        $annoncesSponsoises['datePublication'] = $createDate->format('Y-m-d');
        return $annoncesSponsoises;
    }
    private function arrangeCategorie($categoriesArray)
    {
        foreach ($categoriesArray as $categorie) {
            if (!in_array($categorie['categorie'], $this->categories)) {
                //array_push($this->categories, $categorie['categorie']);
                $this->categories[$categorie['categorie']]['id'] = $categorie['idcategorie'];
                $this->categories[$categorie['categorie']]['sousCategorie'][] = $categorie['sousCategorie'];
                $this->categories[$categorie['categorie']][$categorie['sousCategorie']]['id'] = $categorie['id_sousCategorie'];
            }
        }
    }

    /**
     * Affiche la page de liste des livres triés
     *
     */
    private function getLivresTries()
    {
        try {
            $reqPDO = new RequetesPDO();
            $livres = $reqPDO->getLivres($this->tri_type, $this->tri_ordre); // valeur par defaut declarées si haut
            $vue = new Vue("LivresTri", array(
                'livres' => $livres,
                'type'   => $this->tri_type,
                'ordre'  => $this->tri_ordre
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Affiche du resultat d'une recherche 
     *
     */
    private function getLivresPar($annee, $recherche_titreContient)
    {
        try {
            $reqPDO = new RequetesPDO();
            $livres = $reqPDO->getLivresPar($annee, $recherche_titreContient);
            $vue = new Vue("LivresRecherche", array(
                'titreContient' => $recherche_titreContient,
                'annee' => $annee,
                'livres' => $livres,
                'type'   => $this->tri_type,
                'ordre'  => $this->tri_ordre
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Méthode qui affiche un message d'erreur
     * 
     */
    private function erreur($msgErreur, $codeErreur)
    {
        if ($codeErreur === 1) {
            $vue = new Vue("LivresTri", array(
                'msgErreur' => $msgErreur,
                'type'   => $this->tri_type,
                'ordre'  => $this->tri_ordre
            ));
        } elseif ($codeErreur === 2) {

            $vue = new Vue("LivresRecherche", array(
                'msgErreur' => $msgErreur,
                'annee'   => $this->recherche_annee,
                'titreContient'   => $this->recherche_titreContient
            ));
        } else {

            $vue = new Vue("Erreur", array('msgErreur' => $msgErreur));
        }
    }
}
