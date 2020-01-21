<?php

/* pour le chargement automatique des classes dans vendor */
require_once 'vendor/autoload.php';

require_once 'src/mf/utils/ClassLoader.php';

$loader = new mf\utils\ClassLoader("src");
$loader->register();

use mf\auth\Authentification;
use giftBoxApp\auth\GiftBoxAuthentification;
use mf\router\Router;




$init = parse_ini_file("conf/config.ini");

$config = [
    'driver'    => $init["type"],
    'host'      => $init["host"],
    'database'  => $init["name"],
    'username'  => $init["user"],
    'password'  => $init["pass"],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '' ];

/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection( $config ); /* configuration avec nos paramètres */
$db->setAsGlobal();            /* visible de tout fichier */
$db->bootEloquent();           /* établir la connexion */

session_start();

$router = new \mf\router\Router();

$router->addRoute('prestations', '/prestations/', '\giftBoxApp\control\GiftBoxController', 'viewPrestations',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('categorie', '/categorie/', '\giftBoxApp\control\GiftBoxController', 'viewCategoriePrestations',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('prestation', '/prestation/', '\giftBoxApp\control\GiftBoxController', 'viewPrestation',GiftBoxAuthentification::ACCESS_LEVEL_USER);

//-routes for coffret
$router->addRoute('formCoffret', '/formCoffret/', '\giftBoxApp\control\GiftBoxController', 'viewFormCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('addCoffret', '/addCoffret/', '\giftBoxApp\control\GiftBoxController', 'addCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('coffrets', '/coffrets/', '\giftBoxApp\control\GiftBoxController', 'viewCoffrets',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('selectCoffret', '/selectCoffret/', '\giftBoxApp\control\GiftBoxController', 'selectCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('addPrestationToCoffret', '/addPrestationToCoffret/', '\giftBoxApp\control\GiftBoxController', 'addPrestationToCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('unSelectCoffret', '/unSelectCoffret/', '\giftBoxApp\control\GiftBoxController', 'unSelectCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('suppPrestationUser', '/suppPrestationUser/', '\giftBoxApp\control\GiftBoxController', 'suppPrestationUser',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('suppCoffret', '/suppCoffret/', '\giftBoxApp\control\GiftBoxController', 'suppCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('validerCoffret', '/validerCoffret/', '\giftBoxApp\control\GiftBoxController', 'validerCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('payerCoffret', '/payerCoffret/', '\giftBoxApp\control\GiftBoxController', 'payerCoffret',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('cadeau', '/cadeau/', '\giftBoxApp\control\GiftBoxController', 'cadeau',GiftBoxAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('formMessage', '/formMessage/', '\giftBoxApp\control\GiftBoxController', 'viewFormMessage',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('editMessage', '/editMessage/', '\giftBoxApp\control\GiftBoxController', 'editMessage',GiftBoxAuthentification::ACCESS_LEVEL_USER);

//cagnotte
$router->addRoute('formCagnotte', '/formCagnotte/', '\giftBoxApp\control\GiftBoxController', 'viewCagnotte',GiftBoxAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('addCagnotte', '/addCagnotte/', '\giftBoxApp\control\GiftBoxController', 'addCagnotte',GiftBoxAuthentification::ACCESS_LEVEL_NONE);

//profil
$router->addRoute('profil', '/profil/', '\giftBoxApp\control\GiftBoxController', 'viewProfil',GiftBoxAuthentification::ACCESS_LEVEL_USER);

//routes for
$router->addRoute('login', '/login/', '\giftBoxApp\control\GiftBoxAdminController', 'viewLogin',GiftBoxAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('checkLogin', '/checkLogin/', '\giftBoxApp\control\GiftBoxAdminController', 'checkLogin',GiftBoxAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('logout', '/logout/', '\giftBoxApp\control\GiftBoxAdminController', 'logout',GiftBoxAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('signup', '/signup/', '\giftBoxApp\control\GiftBoxAdminController', 'signup',GiftBoxAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('checksignup', '/checksignup/', '\giftBoxApp\control\GiftBoxAdminController', 'checksignup',GiftBoxAuthentification::ACCESS_LEVEL_NONE);

//routes for admin
$router->addRoute('formPrestation', '/formPrestation/', '\giftBoxApp\control\GiftBoxAdminController', 'viewTabPrest',GiftBoxAuthentification::ACCESS_LEVEL_ADMIN);
$router->addRoute('createPrestation', '/createPrestation/', '\giftBoxApp\control\GiftBoxAdminController', 'createPrestation',GiftBoxAuthentification::ACCESS_LEVEL_ADMIN);
$router->addRoute('suppPrestation', '/suppPrestation/', '\giftBoxApp\control\GiftBoxAdminController', 'suppPrestation',GiftBoxAuthentification::ACCESS_LEVEL_ADMIN);
$router->addRoute('desacPrestation', '/desacPrestation/', '\giftBoxApp\control\GiftBoxAdminController', 'desacPrestation',GiftBoxAuthentification::ACCESS_LEVEL_ADMIN);
$router->addRoute('activerPrestation', '/activerPrestation/', '\giftBoxApp\control\GiftBoxAdminController', 'activerPrestation',GiftBoxAuthentification::ACCESS_LEVEL_ADMIN);

$router->setDefaultRoute('/prestations/');

$router->run();


?>