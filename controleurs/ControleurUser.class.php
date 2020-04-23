<?php
class ControleurUser extends Controleur
{
     private $item    = "";
     private $action  = "";
     private $id      = "";



     /**
      * Contrôle de l'URL pour exécuter l'action qui en découle
      *
      */
     public function __construct()
     {

          if (isset($_SESSION['id'])) {

               $this->item   = isset($_GET['item'])   ? $_GET['item']   : "user";
               $this->action = isset($_GET['action']) ? $_GET['action'] : "get";
               $this->id     = isset($_GET['id'])     ? $_GET['id']     : "";

               if (in_array($this->item, ["user"])) {
                    if (in_array($this->action, ["get", "ajouter", "modifier", "supprimer"])) {
                         $item   = ucfirst($this->item);
                         $action = $this->action;
                         if ($action === "get") $item .= "s";
                         $methode = $action . $item;
                         $this->$methode();
                         exit;
                    }
                    if ($this->action === "deconnecter") {
                         $this->deconnecter();
                         exit;
                    }
                    throw new exception("Action invalide");
               }
               throw new exception("url non invalide");
               // si l'utilisateur veut s'inscrire 
          } elseif (isset($_POST['action']) && $_POST['action'] == 'ajouter' && isset($_POST['item']) && $_POST['item'] == 'user') {
               $this->ajouterUser();
          } else {
               $this->connecter();
          }
     }
     /**
      * connecter un utilisateur 
      *
      * @return void
      */
     private function connecter($msg = false)
     {
          if (isset($_POST['Envoyer'])) {
               if ($this->DBgetConnexion($_POST['email'], $_POST['password'])) {
                    new ControleurAnnonce();
               } else {
                    list($user['email'], $user['password']) = [$_POST['email'], $_POST['password']];
                    $vue = new Vue("UserConnexion", array(
                         'user' => $user,
                         'msg' => 'Email ou mot de passe incorrect.',
                    ), 'gabarit');
               }
          } else {
               $vue = new Vue("UserConnexion", array(
                    'msg' => $msg,
                    '' => null,
                    '' => null,
               ), 'gabarit');
          }
     }
     /**
      * deconnecter un utilisateur 
      *
      * @return void
      */
     private function deconnecter()
     {
          session_destroy();
          unset($_SESSION['id']);
          unset($_SESSION['nom']);
          unset($_SESSION['admin']);

          // afficher la page connexion avec un message 
          $this->connecter(' Vous avez été deconnecté');
     }

     /**
      * ajouter un user
      *
      * @return void
      */
     private function ajouterUser()
     {
          try {

               if (isset($_POST['envoyer'])) {
                    $villes = $this->DBgetVilles();
                    $erreursHydrate = null;
                    $erreurMysql = null;
                    $oUser = new User();
                    $erreursHydrate = $oUser->hydrate(["nom" => $_POST['nom'], "password" => $_POST['password'], "email" => $_POST['email'], 'ville_idVille' => $_POST['ville_idVille'], 'passwordConfirm' => $_POST['passwordConfirm']]);
                    if (count($erreursHydrate) !== 0) { // si='il y a pas d'erreur d'hydratation 
                         $user = $oUser->getItem();
                         $vue = new Vue("UserAjoutUser", array(
                              'user' => $user,
                              'villes' => $villes,
                              'erreursHydrate' => $erreursHydrate,
                         ), 'gabarit');
                    } else {
                         $user = $oUser->getItem();
                         $user = $this->encrypteMdp($user);
                         $reqPDO = new RequetesPDO();
                         if ($erreurMysql = $reqPDO->ajouterItem('user', $user) == true) { // l'ajout du dans la base de donnees 
                              $vue = new Vue("UserConnexion", array(
                                   'user' => $user,
                                   'msg' => 'Votre compte a été crée veuillez vous vous connecter',
                              ), 'gabarit'); // redirection vers a la page de connexion
                         } else { // ajout non effectué 
                              $vue = new Vue("UserAjoutUser", array(
                                   "erreurMysql" => $erreurMysql,
                                   'villes' => $villes,
                                   'user' => $user,
                              ), 'gabarit');
                         }
                    }
               } else {
                    $villes = $this->DBgetVilles();
                    $vue = new Vue("UserAjoutUser", array(
                         'villes' => $villes,
                         'user' => null,
                    ), 'gabarit');
               }
          } catch (Exception $e) {
               $this->erreur($e->getMessage());
          }
     }


     /**
      * msgErreur
      *cette fonction affiche la vu erreur pour les catchée
      * @param  string $msgErreur
      *
      * @return void
      */
     private function erreur($msgErreur)
     {
          $vue = new Vue("Erreur", array('msgErreur' => $msgErreur), 'gabaritErreur');
     }
     /**
      * encrypteMdp
      *
      * @param  array $pwd
      *
      * @return array
      */
     private function encrypteMdp($pwd)
     {
          $pwd['password'] = password_hash($pwd['password'], PASSWORD_DEFAULT);
          return $pwd;
     }
}
