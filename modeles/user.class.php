<?php

/**
 * User
 * gere les utilisateur 
 * 
 * @package    
 * @subpackage Modele
 * @author     Ammar Otmane
 */
class User extends Entite
{
    protected $nom = NULL; // nommage identique au champ MySQL correspondant
    protected $password = NULL; // nommage identique au champ MySQL correspondant
    protected $email = null;
    protected $image_idimage = 1; // a cause de covid 19
    protected $ville_idVille;
    protected $iduser = null;


    public function __construct()
    {
    }




    /**
     * Validation confirmation de password
     *
     * @param  mixed $conPwd
     *
     * @return void
     */
    protected function setPasswordConfirm($connPwd)
    {

        if ($connPwd !== $this->password)
            $this->erreursHydrate['passwordConfirm'] =  "Doit etre identique au mot de passe.";
    }
    /**
     * Validation de la ville
     *
     * @param  mixed $nom
     *
     * @return void
     */
    protected function setVille_idVille($ville_idVille = 1)
    {
        $this->ville_idVille = $ville_idVille;
    }
    /**
     * Validation du nom
     *
     * @param  mixed $nom
     *
     * @return void
     */
    protected function setNom($nom = NULL)
    {
        $regExp = '/\S{2,}/';
        if (!preg_match($regExp, $nom)) {
            $this->erreursHydrate['nom'] = "Au moins 2 caractères.";
        }
        $this->nom = trim($nom);
    }

    /**
     * Validation du password
     *
     * @param  mixed $password
     *
     * @return void
     */
    protected function setPassword($password = NULL)
    {
        $regExp = '/\S{8,}/';
        if (!preg_match($regExp, $password))
            $this->erreursHydrate['password'] =  "Au moins 8 caractères.";

        $this->password = trim($password);
    }
    /**
     * Validation del'email
     *
     * @param  mixed $email
     *
     * @return void
     */
    protected function setEmail($email = NULL)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->erreursHydrate['email'] =  "Email non valide.";
        }
        $this->email = trim($email);
    }


    /**
     * DBgetVilles
     *cette fonction recupere les ville de la base de donnee
     * @param  
     *
     * @return array
     */
    public function DBgetVilles()
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

    /**
     * DBgetConnexion
     *cette fonction recupere verifie la connexion
     * @param  string $email
     * @param  string $mdp
     *
     * @return array
     */
    public function DBgetConnexion($email, $mdp)
    {
        try {
            $sPDO = SingletonPDO::getInstance();
            $oPDOStatement = $sPDO->prepare(
                "SELECT password ,nom,iduser 
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
                    $_SESSION['admin'] = true; // si admin 
                }
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
