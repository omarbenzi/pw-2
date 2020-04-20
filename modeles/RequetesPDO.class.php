<?php

class RequetesPDO
{

    public function getLivres($type = "annee", $ordre = "desc")
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT LI.id_livre, LI.id_auteur, LI.titre, LI.annee, CONCAT(AU.nom,' ', AU.prenom) AS auteur
                 FROM auteur AU 
                 INNER JOIN livre LI ON AU.id_auteur = LI.id_auteur
            ORDER  BY {$type} {$ordre}"
            );
            $oPDOStatement->execute();
            $livres = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function getLivresPar($annee = null, $titreContient = null)
    {

        if ($annee !== null || $titreContient !== null) {
            try {
                $sql_param = "WHERE";
                $sql = "SELECT LI.id_livre, LI.id_auteur, LI.titre, LI.annee, CONCAT(AU.nom,' ', AU.prenom) AS auteur
            FROM auteur AU 
            INNER JOIN livre LI ON AU.id_auteur = LI.id_auteur ";
                if (!is_null($annee)) {
                    $sql .= " {$sql_param} LI.annee = :annee";
                    $sql_param = "AND";
                }
                if (!is_null($titreContient)) {
                    $sql .= " {$sql_param} LI.titre LIKE :titreContient";
                    $titreContient = "%{$titreContient}%";
                }
                $sPDO = SingletonPDO::getInstance();
                $oPDOStatement = $sPDO->prepare(
                    $sql
                );

                if ($annee !== null) $oPDOStatement->bindParam(':annee', $annee, PDO::PARAM_INT);
                if ($titreContient  !== null) $oPDOStatement->bindParam(':titreContient', $titreContient, PDO::PARAM_STR);
                $oPDOStatement->execute();
                if ($oPDOStatement->rowCount() == 0) {
                    throw new exception('Aucun résultat..', 2);
                }
                $livres = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
                return $livres;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    public function getAuteurs()
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT DISTINCT AU.id_auteur,  CONCAT(AU.nom,' ', AU.prenom) AS auteur,
                (SELECT COUNT(*) FROM livre LI WHERE LI.id_auteur = AU.id_auteur) AS Nb_livres
                FROM auteur AU"
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
    public function getAuteur($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * 
                FROM auteur WHERE id_auteur = :id_auteur"
            );
            $oPDOStatement->bindValue(":id_auteur", $id, PDO::PARAM_INT);
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

    public function deleteAuteur($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $sPDO->beginTransaction();
            $oPDOStatement = $sPDO->prepare(
                "DELETE FROM livre WHERE id_auteur = :id_auteur"
            );
            $oPDOStatement->bindValue(":id_auteur", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
            $oPDOStatement = $sPDO->prepare(
                "DELETE FROM auteur WHERE id_auteur = :id_auteur"
            );
            $oPDOStatement->bindValue(":id_auteur", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
            $sPDO->commit();
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function deleteLivre($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();

            $oPDOStatement = $sPDO->prepare(
                "DELETE FROM livre WHERE id_livre = :id_livre"
            );
            $oPDOStatement->bindValue(":id_livre", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }
    public function getConnexion($email, $mdp)
    {
        try {
            $opensslMethode = "AES-256-CBC";
            $opensslMdp = "aqzsedrf123";
            $opensslVecteurInitialisation = "123abc456defg789";

            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT password,admin,nom 
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


    public function getAdmin($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * 
                FROM administrateur WHERE id_administrateur = :id_administrateur"
            );
            $oPDOStatement->bindValue(":id_administrateur", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun résultat..');
            }
            $admin = $oPDOStatement->fetch();
            return $admin;
        } catch (PDOException $e) {
            throw $e;
        }
    }
    public function deleteAdmin($id)
    {
        try {
            $sPDO = SingletonPDO::getInstance();

            $oPDOStatement = $sPDO->prepare(
                "DELETE FROM administrateur WHERE id_administrateur = :id_administrateur"
            );
            $oPDOStatement->bindValue(":id_administrateur", $id, PDO::PARAM_INT);
            $oPDOStatement->execute();
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
    public function getVilles()
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT * FROM `ville` "
            );
            $oPDOStatement->execute();
            if ($oPDOStatement->rowCount() == 0) {
                throw new exception('Aucun résultat..');
            }
            $admins = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
            return $admins;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
