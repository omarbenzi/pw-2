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
            $annoncesSponsoises = $this->DBgetAnnoncesSponsorises();
            $categories = $this->getCategories();
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
            $annonces = $this->getAnnoncebySousCategory($this->id);
            $annonces = array_map(array($this, 'arrangeDate'), $annonces);
            $categories = $this->getCategories();
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

    public function DBgetAnnoncesSponsorises()
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
    public function getCategories()
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT souscategorie.id_sousCategorie, souscategorie.nom AS sousCategorie,categorie.idcategorie, categorie.nom AS categorie FROM `souscategorie` INNER JOIN `categorie` ON souscategorie.id_categorie = categorie.idcategorie"
            );
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun résultat..', 3);
            }
            $catego = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $catego;
        } catch (PDOException $e) {
            throw $e;
        }
    }
    /**
     * getAnnoncebyId
     *cette fonction recupere une annonce par id
     * @param  int $id
     *
     * @return array
     */
    public function getAnnoncebyId($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * FROM annonce WHERE annonce.idarticle = :id OR annonce.sous-categorie_idsous-categorie = :id"
            );
            $oPDOStatement->bindValue(":id", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun résultat..');
            }
            $autuer = $oPDOStatement->fetch();
            return $autuer;
        } catch (PDOException $e) {
            throw $e;
        }
    }
    /**
     * getAnnoncebySousCategory
     *cette fonction recupere les annonce par id Categorie
     * @param  int $id
     *
     * @return array
     */
    public function getAnnoncebySousCategory($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * FROM annonce WHERE annonce.sous_categorie_id = :id"
            );
            $oPDOStatement->bindValue(":id", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun résultat..');
            }
            $annonces = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $annonces;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
