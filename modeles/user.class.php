<?php

class User extends Entite
{
    protected $nom = NULL; // nommage identique au champ MySQL correspondant
    protected $password = NULL; // nommage identique au champ MySQL correspondant
    protected $email = null;
    protected $image_idimage = 1; // a cause de covid 19
    protected $ville_idVille = 1;
    protected $iduser = null;


    public function __construct()
    {
    }




    /**
     * confirmation de password
     *
     * @param  mixed $nom
     *
     * @return void
     */
    protected function setPasswordConfirm($conPwd)
    {

        if ($conPwd !== $this->password)
            $this->erreursHydrate['passwordConfirm'] =  "Doit etre identique au mot de passe.";
    }
    /**
     * setIdentifiant
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
     * setIdentifiant
     *
     * @param  mixed $nom
     *
     * @return void
     */
    protected function setNom($nom = NULL)
    {
        $regExp = '/\S{8,}/';
        if (!preg_match($regExp, $nom)) {
            $this->erreursHydrate['nom'] = "Au moins 8 caractères.";
        }
        $this->nom = trim($nom);
    }

    /**
     * setMdp
     *
     * @param  mixed $mdp
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
     * set email
     *
     * @param  mixed $mdp
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
}
