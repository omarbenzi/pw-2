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
            $categories = $reqPDO->getCategories();

            $vue = new Vue("Accueil", array(
                'annoncesSponsoises' => $annoncesSponsoises,
                'categories'   => $this->categories,
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }
}
