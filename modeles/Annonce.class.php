<?php

class Annonce extends Entite
{
    protected $idarticle = NULL;
    protected $titre = NULL;
    protected $description = NULL;
    protected $prix = NULL;
    protected $sponsorise = NULL;
    protected $lien;
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


    protected function setImage(array $image)
    {
        // Check if file was uploaded without errors
        if (isset($image["photo"]) && $image["photo"]["error"] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $image["photo"]["name"];
            $filetype = $image["photo"]["type"];
            $filesize = $image["photo"]["size"];

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) {
                $this->erreursHydrate['image'] = "extention non autorisé";
            }

            // Verify file size - 5MB maximum
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) {
                $this->erreursHydrate['image'] = "la taille de l'image ne doit pas depasé 5MB";
            }

            // Verify MYME type of the file
            if (in_array($filetype, $allowed)) {
                $this->lien =   "upload/" . $filename . '-' . uniqid();
                move_uploaded_file($_FILES["photo"]["tmp_name"],  $this->lien);
            } else {
                $this->erreursHydrate['image'] = "Error: There was a problem uploading your file. Please try again.";
            }
        } else {
            $this->erreursHydrate['image'] = "une erreure s'est produite.";
        }
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
    public function addAnnonce($table = 'annonce', $champs) // a retravailler///
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
