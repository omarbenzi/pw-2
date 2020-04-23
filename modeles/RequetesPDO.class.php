<?php

class RequetesPDO
{
    const ERREUR_MYSQL_INTEGRITY_CONSTRAINT_VIOLATION = "23000";

    public function ajouterItem($table, $champs)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $req = "INSERT INTO " . $table . " SET ";
            foreach ($champs as $nom => $valeur) {
                $req .= $nom . "=:" . $nom . ", ";
            }
            $req = substr($req, 0, -2);
            $oPDOStatement = $sPDO->prepare($req);
            foreach ($champs as $nom => $valeur) {
                $oPDOStatement->bindValue(":" . $nom, $valeur);
            }
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                return "Ajout non effectué.";
            } else {
                return true;
            }
        } catch (Exception $e) {
            if ($e->getCode() === self::ERREUR_MYSQL_INTEGRITY_CONSTRAINT_VIOLATION) {
                return ucfirst($table) . " déjà présent."; // identifiant administrateur
            } else {
                throw $e;
            }
        }
    }
    public function modifierItem($table, $champs, $id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $req = "UPDATE " . $table . " SET ";
            foreach ($champs as $nom => $valeur) {
                $req .= $nom . "=:" . $nom . ", ";
            }
            $req = substr($req, 0, -2);
            $req .= " WHERE id_{$table} =:id_{$table}";
            $oPDOStatement = $sPDO->prepare($req);
            foreach ($champs as $nom => $valeur) {
                $oPDOStatement->bindValue(":" . $nom, $valeur);
            }
            $oPDOStatement->bindValue(":id_{$table}", $id);
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                return "Modification non effectuée.";
            } else {
                return 0;
            }
        } catch (Exception $e) {
            if ($e->getCode() === self::ERREUR_MYSQL_INTEGRITY_CONSTRAINT_VIOLATION) {
                return ucfirst($table) . " déjà présent."; // identifiant administrateur
            } else {
                throw $e;
            }
        }
    }


    public function getConnexion($email, $mdp)
    {
        try {


            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT password,admin,nom,iduser 
                FROM user WHERE email = :email"
            );
            $oPDOStatement->bindValue(":email", $email, PDO::PARAM_STR);
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                return false;
            }
            $mdp_DB = $oPDOStatement->fetch(PDO::FETCH_ASSOC);
            if (password_verify($mdp, $mdp_DB['password'])) {
                $_SESSION['nom'] = $mdp_DB['nom'];
                $_SESSION['id'] = $mdp_DB['iduser'];
                if ($mdp_DB['admin'] == 1) {
                    $_SESSION['admin'] = true;
                }
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
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
