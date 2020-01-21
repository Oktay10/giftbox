<?php
namespace giftBoxApp\control;

use giftBoxApp\model\Prestation;
use giftBoxApp\model\Categorie;
use giftBoxApp\model\Coffret;
use mf\router\Router;
use giftBoxApp\model\User;
use giftBoxApp\model\Coff_pres;


class GiftBoxController extends \mf\control\AbstractController {

    
    public function __construct(){
        parent::__construct();
    }

    
    public function viewPrestations(){

        if(isset($_GET['tri']))
            $prestations = Prestation::select()->orderBy('prix', $_GET['tri'])->get();
        else {
            $prestations = Prestation::select()->get();
        }
         $categories = Categorie::select()->get();
        
         $vue = new \giftBoxApp\view\GiftBoxView([
            'categories' => $categories,
            'prestations' => $prestations
            ]);

         return $vue->render('prestations');

    }

    public function viewCategoriePrestations(){

        if(isset($_GET['id'])){

            $cat_id = $_GET['id'];
            $categories = Categorie::select()->get();
            $categorie = Categorie::where('id','=',$cat_id)->first();
            if(!is_null($categorie)){
                if(isset($_GET['tri']))
                    $prestations = $categorie->prestations()->orderBy('prix', $_GET['tri'])->get();
                else {
                    $prestations = $categorie->prestations()->get();
                }
            }
            else{
             $prestations = null;
            }
 
            $vue = new \giftBoxApp\view\GiftBoxView([
                'categorie' => $categorie,
                'categories' => $categories,
                'prestations' => $prestations
                ]);
 
            return $vue->render('categoriePrestations');  
 
         }else{

         }

    }

    public function ViewPrestation(){

        $res = "";

         if(isset($_GET['id'])){

            $prestation_id = $_GET['id'];

            $prestation = Prestation::where('id','=',$prestation_id)->first();
            if(!is_null($prestation))
                $categorie  = $prestation->categorie()->first();

                $vue = new \giftBoxApp\view\GiftBoxView([
                    'categorie' => $categorie,
                    'prestation' => $prestation
                    ]);
            
            

            return $vue->render('prestation');

         }

    }

//------------------------------
    public function viewFormCoffret() {
        $route = new Router(); 
        if(isset($_SESSION['coffret']))
        unset($_SESSION['coffret']);

        if(isset($_SESSION['coffret_nom']))
        unset($_SESSION['coffret_nom']);

        if(isset($_SESSION['coffret_etat']))
        unset($_SESSION['coffret_etat']);

        $vue = new \giftBoxApp\view\GiftBoxView('');
        return $vue->render('addCoffret');
    }


    public function addCoffret(){
        $route = new Router();

           if (isset($_SESSION['user_login'])) {

            $user = User::where('username','=',$_SESSION['user_login'])->first();

            $coffret = new \giftBoxApp\model\Coffret;
            $coffret->nom = $_POST['nom'];
            $coffret->id_user = $user->id;
            $coffret->prix = 0;
            $coffret->etat = 'en cours';
            $coffret->date = $_POST['date'];
            $coffret->save();
        
            $route->executeRoute('profil');
        }

    }

    public function viewCoffrets(){
        $route = new Router(); 
        if(isset($_SESSION['user_login'])){
                if(isset($_SESSION['coffret'])){
            $user = User::select()->where('username','=',$_SESSION['user_login'])->first();
            $coffrets = $user->coffrets()->orderBy('id','DESC')->get();
            $coffret = Coffret::select()->where('id','=',$_SESSION['coffret'])->first();

            

            if(isset($_GET['tri']))
                $coff_pres = $coffret->prestations()->orderBy('prix', $_GET['tri'])->get();
            else {
                $coff_pres = $coffret->prestations()->get();
            }

            $vue = new \giftBoxApp\view\GiftBoxView([
                'coffrets' => $coffrets ,
                'coffret' => $coffret ,
                'prestations' => $coff_pres
                ]);

            return $vue->render('coffrets');
                }else{
                    $route->executeRoute('profil');
                }
        }
           
    }

//------------

        public function suppCoffret(){
            $route = new Router();
            $coffret = new \giftBoxApp\model\Coffret();	

            if(isset($_GET['id'])){

                $coff_pres = Coff_pres::select()->where('id_coffret','=',$_GET['id'])->get();

                if(isset($coff_pres)){
                    foreach($coff_pres as $key => $t){
                        $t->delete();
                    }
                }

                //---------
                $cagnotte = \giftBoxApp\model\Cagnotte::select()->where('id_cof','=',$_GET['id'])->get();

                if(isset($cagnotte)){
                    foreach($cagnotte as $key => $t){
                        $t->delete();
                    }
                }

                $coffret = Coffret::where('id', $_GET['id'])->first();
                
                $coffret->delete();
                $this->unSelectCoffret();   
        }
        }

        public function suppPrestationUser(){
            $route = new Router();
            $coff_pres = new \giftBoxApp\model\Coff_pres();	

            if(isset($_GET['id'])){
            
                $coff_pres = Coff_pres::where('id_prestation', $_GET['id'])->where('id_coffret', $_SESSION['coffret'])->first();
                
                $coff_pres->delete();
                
                $route->executeRoute('coffrets');
            }
        }

        public function validerCoffret(){
            $route = new Router();	

            if(isset($_GET['id'])){
            
                $coffet = Coffret::where('id','=',$_GET['id'])->first();
                $coffet->etat = 'valider';
                $coffet->save();

                $route->executeRoute('profil');
            }
        }

        public function viewFormMessage() {
            $route = new Router(); 
    
            $vue = new \giftBoxApp\view\GiftBoxView('');
            return $vue->render('formMessage');
        }

        public function editMessage(){
            $route = new Router();	

            if(isset($_SESSION['coffret'])){
            
                $coffet = Coffret::where('id','=',$_SESSION['coffret'])->first();
                if(isset($_POST['message'])){

                    $coffet->message = $_POST['message'];
                    $coffet->save();

                    $route->executeRoute('profil');
                }
                
            }
        }

        public function viewCagnotte(){
  	
            $vue = new \giftBoxApp\view\GiftBoxView('');
           return $vue->render('formCagnotte');

        }

        public function addCagnotte(){
			$route = new Router();

                $cagnotte = new \giftBoxApp\model\Cagnotte;
                $cagnotte->prix = $_POST['prix'];
                $cagnotte->nom = $_POST['nom'];
                $cagnotte->prenom = $_POST['prenom'];
                $cagnotte->message = $_POST['message'];
                $cagnotte->id_cof = $_GET['id'];
                $cagnotte->save();

                $cagnottes = \giftBoxApp\model\Cagnotte::Where('id_cof','=',$_GET['id'])->get();

                $total = 0;

                foreach($cagnottes as $key => $t){

                    $total += $t->prix;

                }

                $coffret = Coffret::Where('id','=',$_GET['id'])->first();

                if(isset($coffret)){
                    if($coffret->prix <= $total){
                        $coffret->etat = 'payer';
                        $coffret->url = uniqid('',true);
                        $coffret->save();
                    }
                }
				
				$route->executeRoute('profil');
		}

        //------------------->>>payerrrr
        public function payerCoffret(){
            $route = new Router();	

            if(isset($_GET['id'])){
            
                if(isset($_POST['payer'])){
                    if($_POST['payer'] == 'classique'){
                        $coffret = Coffret::where('id','=',$_GET['id'])->first();
                        $coffret->etat = 'payer';
                        $coffret->url = uniqid('',true);
                        $coffret->save();
                    }

                    if($_POST['payer'] == 'cagnotte'){
                        $coffret = Coffret::where('id','=',$_GET['id'])->first();
                        $coffret->etat = 'cagnotte';
                        $coffret->save();
                    }

                }
                
                

                $route->executeRoute('profil');
            }
        }

        public function cadeau(){
            $coffret = Coffret::select()->where('url','=',$_GET['id'])->first();
            $coff_pres = $coffret->prestations()->get(); 

            $cagnottes = \giftBoxApp\model\Cagnotte::Where('id_cof','=',$coffret->id)->get();

            $vue = new \giftBoxApp\view\GiftBoxView([
                'coffret' => $coffret,
                'prestations' => $coff_pres,
                'cagnottes' => $cagnottes
                ]);

            $vue->render('cadeau');
        }


//-------------

    public function viewProfil(){

		if(isset($_SESSION['user_login'])){  
		$user = User::select()->get();
		$user = User::where('username','=',$_SESSION['user_login'])->first();
        $coffrets = $user->coffrets()->get();
        
        $vue = new \giftBoxApp\view\GiftBoxView([
            'user' => $user,
            'coffrets' => $coffrets
            ]);

        return $vue->render('profil');
        }
    }

    public function selectCoffret(){
        $route = new Router(); 
        $coffret = Coffret::select()->where('id','=',$_GET['id'])->first();

            $_SESSION['coffret'] = $_GET['id'];
            $_SESSION['coffret_nom'] = $coffret->nom;
            $_SESSION['coffret_etat'] = $coffret->etat;
        
            $route->executeRoute('coffrets');
       
    }

    public function unSelectCoffret(){
        $route = new Router(); 
        if(isset($_SESSION['coffret']))
        unset($_SESSION['coffret']);

        if(isset($_SESSION['coffret_nom']))
        unset($_SESSION['coffret_nom']);

        if(isset($_SESSION['coffret_etat']))
        unset($_SESSION['coffret_etat']);
        
            $route->executeRoute('profil');
       
    }

    public function addPrestationToCoffret(){
        $route = new Router(); 
        if (isset($_SESSION['coffret'])) {

            $checkPresifExist = Coff_pres::select()->where('id_prestation','=',$_GET['id'])
                                                   ->where('id_coffret','=',$_SESSION['coffret'])
                                                   ->first();

                if(!isset($checkPresifExist)){
                    $coff_pres = new Coff_pres;

                    $coff_pres->id_coffret = $_SESSION['coffret'];
                    $coff_pres->id_prestation = $_GET['id'];
                    
                    $coff_pres->save();
                
                    $route->executeRoute('coffrets');
                }else{
                    $route->executeRoute('prestations');
                }
        }
       
    }


//-------------------------------------

}