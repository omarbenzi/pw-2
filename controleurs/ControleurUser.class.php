<?php
class ControleurUser extends Controleur
{
     private $item    = "livre";
     private $action  = "get";
     private $id      = "";
     protected $opensslMethode = "AES-256-CBC";
     protected $opensslMdp = "aqzsedrf123";
     protected $opensslVecteurInitialisation = "123abc456defg789";

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
               $reqPDO = new RequetesPDO();
               if ($reqPDO->getConnexion($_POST['email'], $_POST['password'])) {
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
                    $reqPDO = new RequetesPDO();
                    $villes = $reqPDO->getVilles();

                    $erreursHydrate = null;
                    $erreurMysql = null;
                    $oUser = new User();
                    $erreursHydrate = $oUser->hydrate(["nom" => $_POST['nom'], "password" => $_POST['password'], "email" => $_POST['email'], 'ville_idVille' => $_POST['ville_idVille'], 'passwordConfirm' => $_POST['passwordConfirm']]);
                    if (count($erreursHydrate) !== 0) {
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
                         if ($erreurMysql = $reqPDO->ajouterItem('user', $user) == true) { // pas d'erruer mysql 
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
                    $reqPDO = new RequetesPDO();
                    $villes = $reqPDO->getVilles();
                    $vue = new Vue("UserAjoutUser", array(
                         'villes' => $villes,
                         'user' => null,
                    ), 'gabarit');
               }
          } catch (Exception $e) {
               $this->erreurAdmin($e->getMessage());
          }
     }
     /**
      * modifier Auteur
      *
      * @return void
      */
     private function modifierAuteur()
     {
          try {

               if (isset($_POST['Envoyer'])) {
                    $erreursHydrate = null;
                    $erreurMysql = null;
                    $oUser = new Auteur(); // instanciation d'un objet
                    $erreursHydrate = $oUser->hydrate(["id_auteur" => $_POST['id_auteur'], "nom" => $_POST['nom'], "prenom" => $_POST['prenom']]);
                    if (count($erreursHydrate) !== 0) { // erreur d'hydratation 
                         $auteur = $oUser->getItem();
                         $vue = new Vue("AdminModificationAuteur", array(
                              'auteur' => $auteur,
                              'erreursHydrate' => $erreursHydrate,
                         ), 'gabaritAdmin');
                    } else {
                         $auteur = $oUser->getItem();
                         $reqPDO = new RequetesPDO();
                         if (!$erreurMysql = $reqPDO->modifierItem('auteur', $auteur, $this->id)) { // pas d'erruer mysql 
                              $this->getAuteurs();
                         } else { // dans le cas ou y a pas eu de modification à la base de donnee
                              $reqPDO = new RequetesPDO();
                              $auteur = $reqPDO->getAuteur($this->id);
                              $vue = new Vue("AdminModificationAuteur", array(
                                   "erreurMysql" => $erreurMysql,
                                   'auteur' => $auteur,
                              ), 'gabaritAdmin');
                         }
                    }
               } else {
                    $reqPDO = new RequetesPDO();
                    $auteurs = $reqPDO->getAuteur($this->id);
                    $vue = new Vue("AdminModificationAuteur", array(
                         'auteur' => $auteurs,
                    ), 'gabaritAdmin');
               }
          } catch (Exception $e) {
               $this->erreurAdmin($e->getMessage());
          }
     }


     /**
      * supprimer Auteur
      *
      * @return void
      */
     private function supprimerAuteur()
     {
          try {
               $reqPDO = new RequetesPDO();
               $reqPDO->deleteAuteur($this->id);
               $auteurs = $reqPDO->getAuteurs();
               $vue = new Vue("AdminListeAuteurs", array(
                    'auteurs' => $auteurs,
               ), 'gabaritAdmin');
          } catch (Exception $e) {
               $this->erreurAdmin("une erreur s'est produite lors de la suppression");
          }
     }

     /**
      * get Livres
      *
      * @return void
      */
     private function getLivres()
     {
          try {
               $reqPDO = new RequetesPDO();
               $livres = $reqPDO->getLivres('LI.id_livre', 'ASC'); //recuperation des livres 
               $vue = new Vue("AdminListeLivres", array(
                    'livres' => $livres,
               ), 'gabaritAdmin');
          } catch (Exception $e) {
               $this->erreurAdmin($e->getMessage(), $e->getCode());
          }
     }
     /**
      * ajouter un Livre
      *
      * @return void
      */
     private function ajouterLivre()
     {
          try {

               if (isset($_POST['Envoyer'])) {
                    $erreursHydrate = null;
                    $erreurMysql = null;
                    $oLivre = new Livre();
                    $erreursHydrate = $oLivre->hydrate(["id_auteur" => $_POST['id_auteur'], "titre" => $_POST['titre'], "annee" => $_POST['annee']]);
                    if (count($erreursHydrate) !== 0) { // erreur d'hydrat
                         $reqPDO = new RequetesPDO();
                         $auteurs = $reqPDO->getAuteurs();
                         $livre = $oLivre->getItem();
                         $vue = new Vue("AdminAjoutLivre", array(
                              'livre' => $livre,
                              'auteurs' => $auteurs,
                              'erreursHydrate' => $erreursHydrate,
                         ), 'gabaritAdmin');
                    } else {
                         $livre = $oLivre->getItem();
                         $reqPDO = new RequetesPDO();
                         if (!$erreurMysql = $reqPDO->ajouterItem('livre', $livre)) { // pas d'erruer mysql 
                              $this->getLivres();
                         } else { // ajout non effectué 
                              $vue = new Vue("AdminListeLivres", array(
                                   "erreurMysql" => $erreurMysql,
                                   'livre' => $livre,
                              ), 'gabaritAdmin');
                         }
                    }
               } else {
                    $reqPDO = new RequetesPDO();
                    $auteurs = $reqPDO->getAuteurs();
                    $vue = new Vue("AdminAjoutLivre", array(
                         'auteurs' => $auteurs,
                    ), 'gabaritAdmin');
               }
          } catch (Exception $e) {
               $this->erreurAdmin($e->getMessage());
          }
     }
     /**
      * modifier un Livre
      *
      * @return void
      */
     private function modifierLivre()
     {
          try {

               if (isset($_POST['Envoyer'])) {
                    $erreursHydrate = null;
                    $erreurMysql = null;
                    $oLivre = new Livre();
                    $erreursHydrate = $oLivre->hydrate(["id_auteur" => $_POST['id_auteur'], "titre" => $_POST['titre'], "annee" => $_POST['annee']]);
                    if (count($erreursHydrate) !== 0) { // erreur d'hydratation 
                         $reqPDO = new RequetesPDO();
                         $auteurs = $reqPDO->getAuteurs();
                         $livre = $oLivre->getItem();
                         $vue = new Vue("AdminModificationLivre", array(
                              'livre' => $livre,
                              'auteurs' => $auteurs,
                              'erreursHydrate' => $erreursHydrate,
                         ), 'gabaritAdmin');
                    } else {
                         $livre = $oLivre->getItem();
                         $reqPDO = new RequetesPDO();
                         if (!$erreurMysql = $reqPDO->modifierItem('livre', $livre, $this->id)) { // pas d'erruer mysql 
                              $this->getLivres();
                         } else { // modification non effectué au niveau de la BD
                              $auteurs = $reqPDO->getAuteurs();
                              $livre['id_livre'] = $this->id; // recuperation l'id a cause au depart venait de l'url non du form (la prop $id)
                              $vue = new Vue("AdminModificationLivre", array(
                                   "erreurMysql" => $erreurMysql,
                                   'auteurs' => $auteurs,
                                   'livre' => $livre,
                              ), 'gabaritAdmin');
                         }
                    }
               } else {
                    $reqPDO = new RequetesPDO();
                    $livre = $reqPDO->getLivre($this->id);
                    $auteurs = $reqPDO->getAuteurs();
                    $vue = new Vue("AdminModificationLivre", array(
                         'auteurs' => $auteurs,
                         'livre' => $livre,
                    ), 'gabaritAdmin');
               }
          } catch (Exception $e) {
               $this->erreurAdmin($e->getMessage());
          }
     }
     /**
      * supprimer un Livre
      *
      * @return void
      */
     private function supprimerLivre()
     {
          try {
               $reqPDO = new RequetesPDO();
               $reqPDO->deleteLivre($this->id);
               $livres = $reqPDO->getLivres('LI.id_livre', 'ASC');
               $vue = new Vue("AdminListeLivres", array(
                    'livres' => $livres,
               ), 'gabaritAdmin');
          } catch (Exception $e) {
               $this->erreurAdmin("une erreur s'est produite lors de la suppression");
          }
     }

     /**
      * get les Administrateurs
      *
      * @return void
      */
     private function getAdministrateurs()
     {
          try {
               $reqPDO = new RequetesPDO();
               $admins = $reqPDO->getAdmins(); //recuperation des livres ;
               $admins = array_map(array($this, 'decrypteMdp'), $admins);
               $vue = new Vue("AdminListeAdministrateurs", array(
                    'admins' => $admins,
               ), 'gabaritAdmin');
          } catch (Exception $e) {
               $this->erreurAdmin("une erreur s'est produite");
          }
     }
     /**
      * ajouter un Administrateur
      *
      * @return void
      */
     private function ajouterAdministrateur()
     {
          try {

               if (isset($_POST['Envoyer'])) {
                    $erreursHydrate = null;
                    $erreurMysql = null;
                    $oAdmin = new Admin();
                    $erreursHydrate = $oAdmin->hydrate(["identifiant" => $_POST['identifiant'], "mdp" => $_POST['mdp']]);
                    if (count($erreursHydrate) !== 0) { // erreur retourner par l'objet admin
                         $admin = $oAdmin->getItem();
                         $vue = new Vue("AdminAjoutAdministrateur", array( // affichage des erreurs
                              'admin' => $admin,
                              'erreursHydrate' => $erreursHydrate,
                         ), 'gabaritAdmin');
                    } else {
                         $admin = $oAdmin->getItem();
                         $admin = $this->encrypteMdp($admin);
                         $reqPDO = new RequetesPDO();
                         if (!$erreurMysql = $reqPDO->ajouterItem('administrateur', $admin)) { // pas d'erruer mysql 
                              $this->getAdministrateurs();
                         } else { // ajout non effectué 
                              $admin = $oAdmin->getItem();
                              $admin['id_administrateur'] = $this->id; // recuperation l'id a cause au depart venait de l'url et non du form (la prop $id)
                              $vue = new Vue("AdminAjoutAdministrateur", array(
                                   "erreurMysql" => $erreurMysql,
                                   'admin' => $admin,
                              ), 'gabaritAdmin');
                         }
                    }
               } else {
                    $reqPDO = new RequetesPDO();
                    $auteurs = $reqPDO->getAuteurs();
                    $vue = new Vue("AdminAjoutAdministrateur", array(
                         '' => null,
                    ), 'gabaritAdmin');
               }
          } catch (Exception $e) {
               $this->erreurAdmin($e->getMessage());
          }
     }

     /**
      * modifier un  Administrateur
      *
      * @return void
      */
     private function modifierAdministrateur()
     {
          try {

               if (isset($_POST['Envoyer'])) {
                    $erreursHydrate = null;
                    $erreurMysql = null;
                    $oAdmin = new Admin();
                    $erreursHydrate = $oAdmin->hydrate(["identifiant" => $_POST['identifiant'], "mdp" => $_POST['mdp']]);
                    if (count($erreursHydrate) !== 0) {
                         $admin = $oAdmin->getItem();
                         $vue = new Vue("AdminModificationAdministrateur", array(
                              'admin' => $admin,
                              'erreursHydrate' => $erreursHydrate,
                         ), 'gabaritAdmin');
                    } else {
                         $admin = $oAdmin->getItem();
                         $admin = $this->encrypteMdp($admin); // Encryption du mot de passe 
                         $reqPDO = new RequetesPDO();
                         if (!$erreurMysql = $reqPDO->modifierItem('administrateur', $admin, $this->id)) { // pas d'erruer mysql 
                              $this->getAdministrateurs();
                         } else { // modification non effectué 
                              $admin = $oAdmin->getItem();
                              $admin['id_administrateur'] = $this->id; // recuperation l'id a cause au depart venait de l'url non du form (la prop $id)
                              $vue = new Vue("AdminModificationAdministrateur", array(
                                   "erreurMysql" => $erreurMysql,
                                   'admin' => $admin,
                              ), 'gabaritAdmin');
                         }
                    }
               } else {
                    $reqPDO = new RequetesPDO();
                    $admin = $reqPDO->getAdmin($this->id);
                    $admin = $this->decrypteMdp($admin);
                    $vue = new Vue("AdminModificationAdministrateur", array(
                         'admin' => $admin,
                    ), 'gabaritAdmin');
               }
          } catch (Exception $e) {
               $this->erreurAdmin($e->getMessage());
          }
     }

     /**
      * supprimer un Administrateur
      *
      * @return void
      */
     private function supprimerAdministrateur()
     {
          try {
               $reqPDO = new RequetesPDO();
               $reqPDO->deleteAdmin($this->id);
               $admins = $reqPDO->getAdmins(); //recuperation des livres ;
               $admins = array_map(array($this, 'decrypteMdp'), $admins);
               $vue = new Vue("AdminListeAdministrateurs", array(
                    'admins' => $admins,
               ), 'gabaritAdmin');
          } catch (Exception $e) {
               $this->erreurAdmin("une erreur s'est produite lors de la suppression");
          }
     }
     /**
      * Méthode qui affiche une page d'erreur
      *
      */
     private function erreurAdmin($msgErreur)
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
     private function encrypteMdp($admin)
     {
          $admin['password'] = password_hash($admin['password'], PASSWORD_DEFAULT);
          return $admin;
     }


     /**
      * decrypteMdp 
      *
      * @param  array $admin
      *
      * @return array
      */
     private function decrypteMdp($admin)
     {
          $admin['mdp'] = openssl_decrypt(
               $admin['mdp'],
               $this->opensslMethode,
               $this->opensslMdp,
               NULL,
               $this->opensslVecteurInitialisation
          );

          return $admin;
     }
}
