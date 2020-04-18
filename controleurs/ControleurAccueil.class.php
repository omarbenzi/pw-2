<?php

class ControleurAccueil
{

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
            //$categories = $reqPDO->getCategories();
            $annoncesSponsoises = array_map(array($this, 'arrangeDate'), $annoncesSponsoises);

            $vue = new Vue("Accueil", array(
                'annoncesSponsoises' => $annoncesSponsoises,
                //'categories'   => $this->categories,
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
}
