<?php

/**
 * ControleurAccueil
 * gere la page d'accueil
 * 
 * @package    
 * @subpackage Controleur
 * @author     Ammar Otmane
 */
class ControleurAccueil
{

    public function __construct()
    {
        new ControleurAnnonce();
    }

    /**
     * Affiche la page d'accueil 
     *
     */
    public function accueil()
    {
    }
}
