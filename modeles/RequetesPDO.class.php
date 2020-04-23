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
                return ucfirst($table) . " déjà présent."; // prevention d'avoir 2 user avec le meme email... ne pas utilisé dans notre cas il faudra changer la cle primaire de la table user pour email
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
            throw $e;
        }
    }
}
