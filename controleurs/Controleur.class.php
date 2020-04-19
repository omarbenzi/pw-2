<?php

class Controleur
{

    public static $base_uri = "\/pw2\/pw-2\/"; //"\/tp2-php\/";

    private $controleurs = array(
        ""          => "ControleurAccueil",
        "annonce"    => "ControleurAnnonce",
        "user"     => "ControleurUser"
    );

    /**
     * Constructeur qui valide l'URI et instancie le controleur correspondant
     *
     */
    public function __construct()
    {
        try {
            $regExp = '/^' . self::$base_uri . '([^\?]*)(\?.*)?$/';
            $requestUri = strtolower($_SERVER["REQUEST_URI"]);
            if (preg_match($regExp, $requestUri, $result)) {
                foreach ($this->controleurs as $uri => $controleur) {
                    if ($uri == $result[1]) {
                        new $controleur;
                        exit;
                    }
                }
            }
            throw new exception('URL non valide.');
        } catch (Exception $e) {
            $this->erreur($e->getMessage());
        }
    }

    /**
     * Méthode qui affiche une page d'erreur
     *
     */
    private function erreur($msgErreur)
    {
        $vue = new Vue("Erreur", array('msgErreur' => $msgErreur), 'gabaritErreur');
    }
}
