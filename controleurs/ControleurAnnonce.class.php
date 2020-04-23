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

            if (in_array($this->item, ["annonce", "annoncesSponsorise", "annonceByCategory"])) {
                if (in_array($this->action, ["get", "ajouter", "modifier", "supprimer"])) {
                    $item   = ucfirst($this->item);
                    $action = $this->action;
                    if ($action === "get") $item .= "s";
                    $methode = $action . $item;
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
    /**
     * Affiche les annonces Sponsorises
     *
     */
    private function getAnnoncesSponsorises()
    {
        try {
            $reqPDO = new RequetesPDO();
            $annoncesSponsoises = $reqPDO->getAnnoncesSponsorises();
            $categories = $reqPDO->getCategories();
            $this->categories = $this->arrangeCategorie($categories);
            $annoncesSponsoises = array_map(array($this, 'arrangeDate'), $annoncesSponsoises);
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
    private function getAnnonceByCategorys()
    {
        try {
            $reqPDO = new RequetesPDO();
            $annonces = $reqPDO->getAnnoncebySousCategory($this->id);
            $annonces = array_map(array($this, 'arrangeDate'), $annonces);
            $categories = $reqPDO->getCategories();
            $this->categories = $this->arrangeCategorie($categories);
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
    /**
     * cette fonction arrange le format des categorie
     * dans le un tableau avant les passer à la vu   
     * on croit que cette fonction pourrait etre remplacé par une meilleure requette sql 
     * @param  array $categoriesArray
     *
     * @return array
     */
    private function arrangeCategorie($categoriesArray)
    {
        $categories = [];
        foreach ($categoriesArray as $categorie) {
            if (!in_array($categorie['categorie'], $categories)) {
                $categories[$categorie['categorie']]['id'] = $categorie['idcategorie'];
                $categories[$categorie['categorie']]['sousCategorie'][] = $categorie['sousCategorie'];
                $categories[$categorie['categorie']][$categorie['sousCategorie']]['id'] = $categorie['id_sousCategorie'];
            }
        }
        return $categories;
    }



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
