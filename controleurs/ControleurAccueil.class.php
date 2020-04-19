<?php

class ControleurAccueil
{
    private $categories = [];

    public function __construct()
    {
        $this->accueil();
    }

    /**
     * Affiche la page d'accueil 
     *
     */
    public function accueil()
    {
        try {
            $reqPDO = new RequetesPDO();
            $annoncesSponsoises = $reqPDO->getAnnoncesSponsorises();
            $categories = $reqPDO->getCategories();
            $annoncesSponsoises = array_map(array($this, 'arrangeDate'), $annoncesSponsoises);
            $this->arrangeCategorie($categories);
            print_r($this->categories);
            $vue = new Vue("Accueil", array(
                'annoncesSponsoises' => $annoncesSponsoises,
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
}
