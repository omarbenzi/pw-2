<?php

/**
 * Annonce
 * non utilis�
 * 
 * @package    
 * @subpackage Modele
 * @author     Ammar Otmane
 */
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
            $this->erreursHydrate['titre'] = "Au moins un caract�re.";
        }
        $this->titre = trim($titre);
    }

    protected function setDescription($description = NULL)
    {
        if (trim($description) === "") {
            $this->erreursHydrate['description'] = "Au moins un caract�re.";
        }
        $this->description = trim($description);
    }
    protected function setPrix($prix = NULL)
    {
        if (trim($prix) === "") {
            $this->erreursHydrate['prix'] = "Au moins un caract�re.";
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
                $this->erreursHydrate['image'] = "extention non autoris�e";
            }

            //  verification de la taile 
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) {
                $this->erreursHydrate['image'] = "la taille de l'image ne doit pas depas� 5MB";
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





    /**
     * DBgetAnnoncesSponsorises
     * cette fonction recupere les annonce sopnsorises
     * @param  
     *
     * @return array
     */
    public function DBgetAnnoncesSponsorises()
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * FROM annonce WHERE annonce.sponsorise = (1) "
            );
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun r�sultat..');
            }
            $autuers = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $autuers;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * DBgetCategories
     * cette fonction recupere les categorie
     * @param  
     *
     * @return array
     */
    public function DBgetCategories()
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT sousCategorie.id_sousCategorie, sousCategorie.nom AS sousCategorie,categorie.idcategorie, categorie.nom AS categorie FROM `sousCategorie` INNER JOIN `categorie` ON sousCategorie.id_categorie = categorie.idcategorie"
            );
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun r�sultat..');
            }
            $catego = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $catego;
        } catch (PDOException $e) {
            throw $e;
        }
    }
    /**
     * DBgetAnnoncebyId
     *cette fonction recupere une annonce par id
     * @param  int $id
     *
     * @return array
     */
    public function DBgetAnnoncebyId($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * FROM annonce WHERE annonce.idarticle = :id ORDER BY annonce.datePublication DESC"
            );
            $oPDOStatement->bindValue(":id", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun r�sultat..');
            }
            $autuer = $oPDOStatement->fetch();
            return $autuer;
        } catch (PDOException $e) {
            throw $e;
        }
    }
    /**
     * DBgetAnnoncebySousCategory
     *cette fonction recupere les annonce par id Categorie
     * @param  int $id
     *
     * @return array
     */
    public function DBgetAnnoncebySousCategory($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * FROM annonce WHERE annonce.sous_categorie_id = :id"
            );
            $oPDOStatement->bindValue(":id", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun r�sultat..');
            }
            $annonces = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $annonces;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
