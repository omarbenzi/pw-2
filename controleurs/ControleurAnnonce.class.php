<?php

class ControleurAnnonce
{
    private $tri_type = "annee";
    private $tri_ordre = "desc";
    private $recherche_annee = null;
    private $recherche_titreContient = null;



    public function __construct()
    {
        try {
            $this->item   = isset($_GET['item'])   ? $_GET['item']   : "getAnnoncesSponsorises";
            $this->action = isset($_GET['action']) ? $_GET['action'] : "get";
            $this->id     = isset($_GET['id'])     ? $_GET['id']     : "";

            if (in_array($this->item, ["annonce", "getAnnoncesSponsorises"])) {
                if (in_array($this->action, ["get", "ajouter", "modifier", "supprimer"])) {
                    $item   = ucfirst($this->item);
                    $action = $this->action;
                    if ($action === "get") $item .= "s";
                    $action = $action . $item;
                    new Annonce($action);
                    // $methode = $action . $item;
                    // $this->$methode();
                    exit;
                }
                if ($this->action === "deconnecter") {
                    $this->deconnecter();
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
     * Affiche la page de liste des livres
     *
     */
    private function getLivres()
    {
        try {
            $reqPDO = new RequetesPDO();
            $livres = $reqPDO->getLivres($this->tri_type, $this->tri_ordre);
            $vue = new Vue("Livres", array(
                'livres' => $livres,
                'type'   => $this->tri_type,
                'ordre'  => $this->tri_ordre
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Affiche la page de liste des livres triés
     *
     */
    private function getLivresTries()
    {
        try {
            $reqPDO = new RequetesPDO();
            $livres = $reqPDO->getLivres($this->tri_type, $this->tri_ordre); // valeur par defaut declarées si haut
            $vue = new Vue("LivresTri", array(
                'livres' => $livres,
                'type'   => $this->tri_type,
                'ordre'  => $this->tri_ordre
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Affiche du resultat d'une recherche 
     *
     */
    private function getLivresPar($annee, $recherche_titreContient)
    {
        try {
            $reqPDO = new RequetesPDO();
            $livres = $reqPDO->getLivresPar($annee, $recherche_titreContient);
            $vue = new Vue("LivresRecherche", array(
                'titreContient' => $recherche_titreContient,
                'annee' => $annee,
                'livres' => $livres,
                'type'   => $this->tri_type,
                'ordre'  => $this->tri_ordre
            ));
        } catch (Exception $e) {
            $this->erreur($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Méthode qui affiche un message d'erreur
     * 
     */
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
