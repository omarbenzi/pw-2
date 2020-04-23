<?php

/**
 * ControleurAnnonce
 * gere les annonces
 * 
 * @package    
 * @subpackage Controleur
 * @author     Ammar Otmane
 */
class ControleurAnnonce
{
    private $categories = [];
    private $annonceInstance = null;
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
                    $this->$methode($this->getAnnonceInstance());
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
     * getAnnoncesSponsorises
     * 
     * 
     * @param instance annonceInstance
     *
     * @return void
     */
    private function getAnnoncesSponsorises($annonceInstance)
    {
        try {
            $annoncesSponsoises = $annonceInstance->DBgetAnnoncesSponsorises();
            $categories = $annonceInstance->DBgetCategories();
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
     * getAnnonceByCategorys
     * 
     * 
     * @param instance annonceInstance
     *
     * @return void
     */
    private function getAnnonceByCategorys($annonceInstance)
    {
        try {
            $annonces = $annonceInstance->DBgetAnnoncebySousCategory($this->id);
            $annonces = array_map(array($this, 'arrangeDate'), $annonces);
            $categories = $annonceInstance->DBgetCategories();
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
    /**
     * getAnnonceInstance
     * 
     * 
     * @param  
     *
     * @return instance $this->annonceInstance
     */
    private function getAnnonceInstance()
    {
        if ($this->annonceInstance !== null) {
            return $this->annonceInstance;
        }
        return $this->annonceInstance = new Annonce();
    }


    /**
     * affiche les erreurs 
     * 
     * 
     * @param  string $msgErreur
     *
     * @return 
     */
    private function erreur($msgErreur)
    {
        $vue = new Vue("Erreur", array('msgErreur' => $msgErreur));
    }
}
