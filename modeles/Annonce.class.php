<?php

class Annonce extends Entite
{
    protected $idarticle = NULL;
    protected $titre = NULL;
    protected $description = NULL;
    protected $prix = NULL;
    protected $sponsorise = NULL;
    protected $lien;

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


    protected function setImage(array $image)
    {
        // Check if file was uploaded without errors
        if (isset($image["photo"]) && $image["photo"]["error"] == 0) {
            $permits = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $image["photo"]["name"];
            $filetype = $image["photo"]["type"];
            $filesize = $image["photo"]["size"];

            // verification de l'extention 
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $permits)) {
                $this->erreursHydrate['image'] = "extention non autorisée";
            }

            //  verification de la taile 
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) {
                $this->erreursHydrate['image'] = "la taille de l'image ne doit pas depasé 5MB";
            }

            // verification du type de  MYME 
            if (in_array($filetype, $permits)) {
                $this->lien =   "upload/" . $filename . '-' . uniqid();
                move_uploaded_file($_FILES["photo"]["tmp_name"],  $this->lien);
            } else {
                $this->erreursHydrate['image'] = "une erreure s'est produite..";
            }
        } else {
            $this->erreursHydrate['image'] = "une erreure s'est produite.";
        }
    }




    protected function setSponsorise($sponsorise = 0)
    {

        $this->sponsorise = ($sponsorise);
    }

    public function getAnnoncesSponsorises()
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
    public function addAnnonce($table = 'annonce', $champs) // a retravailler///
    {

        try {
            $instance = $this->getRequestesPDOInstance();
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
