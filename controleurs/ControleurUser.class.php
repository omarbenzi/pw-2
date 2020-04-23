<?php
class ControleurUser extends Controleur
{
     private $item    = "livre";
     private $action  = "get";
     private $id      = "";



     /**
      * Contrôle de l'URL pour exécuter l'action qui en découle
      *
      */
     public function __construct()
     {

          if (isset($_SESSION['id'])) {

               $this->item   = isset($_GET['item'])   ? $_GET['item']   : "livre";
               $this->action = isset($_GET['action']) ? $_GET['action'] : "get";
               $this->id     = isset($_GET['id'])     ? $_GET['id']     : "";

               if (in_array($this->item, ["administrateur", "livre", "user"])) {
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
               throw new exception("Item invalide");
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
               if ($this->getConnexion($_POST['email'], $_POST['password'])) {
                    new ControleurAnnonce();
               } else {
                    list($user['email'], $user['password']) = [$_POST['email'], $_POST['password']];
                    $vue = new Vue("UserConnexion", array(
                         'user' => $user,
                         'msgErreur' => 'Email ou mot de passe incorrect.',
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
                    $villes = $this->getVilles();
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
                                   '' => null,
                                   'msgErreur' => 'Votre compte a été crée',
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
                    $villes = $this->getVilles();
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
      *cette fonction affiche la vu erreur pour catchée
      * @param  string $admin
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
      * @param  array $admin
      *
      * @return array
      */
     private function encrypteMdp($pwd)
     {
          $pwd['password'] = password_hash($pwd['password'], PASSWORD_DEFAULT);
          return $pwd;
     }


     /**
      * getVilles
      *cette fonction recupere les ville de la base de donnee
      * @param  array $admin
      *
      * @return array
      */
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

     /**
      * getVilles
      *cette fonction recupere verifie la connexion
      * @param  array $admin
      *
      * @return array
      */
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
}
