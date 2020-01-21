<?php
namespace giftBoxApp\control;

use giftBoxApp\model\User;
use giftBoxApp\model\Categorie;
use giftBoxApp\model\Prestation;
use giftBoxApp\auth\GiftBoxAuthentification;
use mf\router\Router;

class GiftBoxAdminController extends \mf\control\AbstractController {


    public function __construct(){
        parent::__construct();
    }

    //viewFormulaireLogin
    public function viewLogin(){
        $vue = new \giftBoxApp\view\GiftBoxView('');

        return $vue->render('login');
    }

    public function logout(){

        if(isset($_SESSION['coffret']))
        unset($_SESSION['coffret']);

        if(isset($_SESSION['coffret_nom']))
        unset($_SESSION['coffret_nom']);
        
        $giftBoxAuth = new GiftBoxAuthentification();

        $giftBoxAuth->logout();

        $route = new Router();
        $route->executeRoute('default');
        
    }

    //checkLogin
    public function checkLogin(){

        if(isset($_POST['username'])){
            
            $giftBoxAuth = new GiftBoxAuthentification();
            $route = new Router();

            if($giftBoxAuth->loginUser($_POST['username'], $_POST['password'])){
                
                     $route->executeRoute('default');

            }else{
                $route->executeRoute('default');
            }
        }
    }

    public function signup(){
        
        $vue = new \giftBoxApp\view\GiftBoxView('');

        return $vue->render('signup');
        
    }


    public function checksignup(){
        
            $giftBoxAuth = new GiftBoxAuthentification();
            $route = new Router();
                
        if(isset($_POST['username'])){
            if($_POST['password'] == $_POST['rpassword']){
                $username = filter_input(INPUT_POST, "username", FILTER_DEFAULT);
                $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);
                $fullname = filter_input(INPUT_POST, "fullname", FILTER_DEFAULT);
                if($giftBoxAuth->createUser($username, $password, $fullname)){
                    $route->executeRoute('login');
                }else{
                    $route->executeRoute('signup');
                }
            }else{
                $route->executeRoute('signup');
            }
                
        }
        
        
    }


    //--------------------

        //viewFormulaire
        public function viewTabPrest(){
  	
            $categories = Categorie::select()->get();
            
                $vue = new \giftBoxApp\view\GiftBoxView($categories);
                return $vue->render('formPrestation');
       
           }
           
           public function createPrestation(){
               $route = new Router();
               $app_root = (new \mf\utils\HttpRequest())->root; 

                $checkFileUpload = false;
                $image = "";
               //-----

               if(isset($_POST['valider'])){

                    $fileName = $_FILES['file']['name'];
                    $fileTmpName = $_FILES['file']['tmp_name'];
                    $fileSize = $_FILES['file']['size'];
                    $fileError = $_FILES['file']['error'];
                    $fileType = $_FILES['file']['type'];

                    $fileExt = explode('.',$fileName);
                    $fileActualExt = strtolower(end($fileExt));

                    $allowed = array('jpg','jpeg','png');

                    if(in_array($fileActualExt,$allowed)){
                        if($fileError == 0){
                            if($fileSize < 1000000){
                                $fileNameNew = uniqid('',true).".".$fileActualExt;
                                $fileDestination = "html/images/".$fileNameNew;
                                move_uploaded_file($fileTmpName, $fileDestination);
                                $checkFileUpload = true;
                                $image = $fileNameNew;
                            }
                        }
                    }

               }

               //-----

               if($checkFileUpload == true){
                $prestations = new \giftBoxApp\model\Prestation();	
               
                $prestations->nom = $_POST['nom'];
                $prestations->description = $_POST['description'];
                $prestations->prix = $_POST['prix'];
                $prestations->img = $image;
                $prestations->status = 1;
                $prestations->id_cat = $_POST['type'];
                $prestations->save();
                $route->executeRoute('prestations');
               }else{
                $route->executeRoute('formPrestation');
               }
               
           }
           
           public function suppPrestation(){
               $route = new Router();
               $prestations = new \giftBoxApp\model\Prestation();	
        
               if(isset($_GET['id'])){
               
                   $prestations = Prestation::where('id', $_GET['id'])->first();
                   $prestations->delete();
                   $route->executeRoute('prestations');
               }
           }
           
           public function desacPrestation(){
               $route = new Router();
               $prestations = new \giftBoxApp\model\Prestation();	
               
               if(isset($_GET['id'])){			
                   $prestations = Prestation::find($_GET['id']);
                   $prestations->status = 0;
                   $prestations->save();
                   $route->executeRoute('prestations');
               }
           }

           public function activerPrestation(){
            $route = new Router();
            $prestations = new \giftBoxApp\model\Prestation();	
            
            if(isset($_GET['id'])){			
                $prestations = Prestation::find($_GET['id']);
                $prestations->status = 1;
                $prestations->save();
                $route->executeRoute('prestations');
            }
        }

    //--------------------
}


?>