<?php

class Annonce extends Entite
{
    protected $idarticle = NULL;
    protected $titre = NULL;
    protected $description = NULL;
    protected $prix = NULL;
    protected $sponsorise = NULL;
    private static $reqPDOInstance = null;
    public function __construct()
    {
    }

    protected function setIdarticle($idarticle = NULL)
    {
        $this->idarticle = $idarticle;
    }

    protected function setTitre($titre = NULL)
    {
        if (trim($titre) === "") {
            $this->erreursHydrate['titre'] = "Au moins un caractère.";
        }
        $this->titre = trim($titre);
    }

    protected function setDescription($description = NULL)
    {
        if (trim($description) === "") {
            $this->erreursHydrate['description'] = "Au moins un caractère.";
        }
        $this->description = trim($description);
    }
    protected function setPrix($prix = NULL)
    {
        if (trim($prix) === "") {
            $this->erreursHydrate['prix'] = "Au moins un caractère.";
        }
        $this->prix = trim($prix);
    }
    protected function setSponsorise($sponsorise = 0)
    {

        $this->sponsorise = ($sponsorise);
    }

    public function getAnnonceSponsorise()
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * FROM annonce WHERE annonce.sponsorise = (1) "
            );
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun résultat..', 3);
            }
            $autuers = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $autuers;
        } catch (PDOException $e) {
            throw $e;
        }
    }
    public function addAnnonce($table = 'annonce', $champs)
    {

        try {
            $instance = $this->getRequestesPDO();
            $instance->ajouterItem($table, $champs);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    private function getRequestesPDO()
    {
        if (is_null(self::$reqPDOInstance)) {
            self::$reqPDOInstance = new RequetesPDO();
        }
        return self::$reqPDOInstance;
    }



    // public function getCategories()
    // {
    //     try {
    //         $sPDO = SingletonPDO::getInstance();
    //         $oPDOStatement = $sPDO->prepare(
    //             "SELECT categorie.nom FROM categorie "
    //         );
    //         $oPDOStatement->execute();
    //         if ($oPDOStatement->rowCount() == 0) {
    //             throw new exception('Aucun résultat..', 3);
    //         }
    //         $autuers = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
    //         return $autuers;
    //     } catch (PDOException $e) {
    //         throw $e;
    //     }
    // }
    // public function getSousCategories()
    // {
    //     try {
    //         $sPDO = SingletonPDO::getInstance();
    //         $oPDOStatement = $sPDO->prepare(
    //             "SELECT sous-categorie.nom FROM sous-categorie "
    //         );
    //         $oPDOStatement->execute();
    //         if ($oPDOStatement->rowCount() == 0) {
    //             throw new exception('Aucun résultat..', 3);
    //         }
    //         $autuers = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
    //         return $autuers;
    //     } catch (PDOException $e) {
    //         throw $e;
    //     }
    // }
}
