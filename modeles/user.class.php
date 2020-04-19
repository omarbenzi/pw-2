<?php

class Admin extends Entite
{
    protected $nom = NULL; // nommage identique au champ MySQL correspondant
    protected $password = NULL; // nommage identique au champ MySQL correspondant
    protected $email = null;


    public function __construct()
    {
    }




    /**
     * setIdentifiant
     *
     * @param  mixed $nom
     *
     * @return void
     */
    protected function setnom($nom = NULL)
    {
        $regExp = '/\S{8,}/';
        if (!preg_match($regExp, $identifiant)) {
            $this->erreursHydrate['nom'] = "Au moins 8 caractères.";
        }
        $this->nom = trim($identifiant);
    }

    /**
     * setMdp
     *
     * @param  mixed $mdp
     *
     * @return void
     */
    protected function setpassword($password = NULL)
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
    protected function setemail($email = NULL)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $this->erreursHydrate['email'] =  "Meail non valide.";
        }

        $this->password = trim($email);
    }
}
