<?php
namespace giftBoxApp\view;

class GiftBoxView extends \mf\view\AbstractView {
  
    public function __construct( $data ){
        parent::__construct($data);
    }


    private function renderHeader(){
        // return '<h1>GiftBoxApp</h1>';
    }

    private function renderSelectedCoffret(){
        $router = new \mf\router\Router(); 
        $res = "";
         $res .= "<div class='unselectCon col s12 m12 l12' >";
         $res .= "<a id='unSelect' class='unSelect' href='".$router->urlFor('unSelectCoffret',['id' => $_SESSION['coffret']])."'> unSelect Coffret : ".$_SESSION['coffret_nom']."</a>";
         $res .= "</div>";
            


            return $res;
    }
    

    private function renderFooter(){
        return 'La super app créée en Licence Pro &copy;2018';
    }

    
    private function renderPrestations(){

        $router = new \mf\router\Router(); 
        $app_root = (new \mf\utils\HttpRequest())->root;       

        $res = "<article class='row'>";
        $res .= "<h2 class='title col s12 m12 l12'>Liste des prestations</h2>"; 
        
        $res .= "<section class='col s12 m3 l3 row'>";
        $res .= "<div class='cat col s12 m12 l12 row'>";

        foreach($this->data['categories'] as $key => $t){

            $res .= "<a class='cat-item col s3 m12 l12' href='".$router->urlFor('categorie',['id' => $t->id])."'>$t->nom</a>";
        }



        $res .= "</div></section>";

        
        

        $res .= "<section class='col s12 m9 l9 row'>";

        $res .= "<div class='col s12 m12 l12 row'>";
        $res .= "<a class='tri col offset-s4 offset-m4 offset-l4 s2 m2 l2' href='".$router->urlFor('prestations',['tri' => 'DESC'])."'>DESC</a>&nbsp;&nbsp;";
        $res .= "<a class='tri col s2 m2 l2' href='".$router->urlFor('prestations',['tri' => 'ASC'])."'>ASC</a>";
        $res .= "</div>";

        foreach($this->data['prestations'] as $key => $t){

            if(isset($_SESSION['user_login']) && $_SESSION['access_level'] == 200){

                $res .= "<div class='col s12 m6 l4 prestation row'>";
                    $res .= "<div class='row pres_info col s12 m12 l12'>";
                    $res .= "<span class='col s10 m10 l10'>$t->nom</span>";
                    $res .= "<p class='col s2 m2 l2'>$t->prix</p>";
                    $res .= "</div>";
                    $res .= "<a class='imgContainer' href='".$router->urlFor('prestation',['id' => $t->id])."'>";
                    $res .= "<img class='imgPres' src='$app_root/html/images/$t->img' /></a>";

                    $res .= "<div class='row pres_info col s12 m12 l12'>";
                    $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('suppPrestation')."?id=$t->id'>Supprimer</a>";
                    if($t->status == 1){
                    
                    $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('desacPrestation')."?id=$t->id'>Desactiver</a>";
                    }
                    else
                    {
                        $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('activerPrestation')."?id=$t->id'>Activer</a>";		
                    }
                
                    $res .= "</div>";
                $res .= "</div>";

            }else{
                if($t->status == 1){
                    
                    $res .= "<div class='col s12 m6 l4 prestation row'>";
                    $res .= "<div class='row pres_info col s12 m12 l12'>";
                    $res .= "<span class='col s10 m10 l10'>$t->nom</span>";
                    $res .= "<p class='col s2 m2 l2'>$t->prix</p>";
                    $res .= "</div>";
                    $res .= "<a class='imgContainer' href='".$router->urlFor('prestation',['id' => $t->id])."'>";
                    $res .= "<img class='imgPres' src='$app_root/html/images/$t->img' /></a>";

                    if (isset($_SESSION['coffret'])) {
                        if($_SESSION['coffret_etat'] == 'en cours'){
                            $res .= "<div class='row pres_info col s12 m12 l12'>";
                            $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('addPrestationToCoffret')."?id=$t->id'>Ajouter</a>";
                            $res .= "</div>";
                        } 
                    }
                    $res .= "</div>";
                }
            }
            
        }

        $res .= "</section>";
        $res .= "</article>";
        
        return $res;
    }

    private function renderCategoriePrestations(){
        $router = new \mf\router\Router(); 
        $app_root = (new \mf\utils\HttpRequest())->root; 

        
        
        if(!is_null($this->data['categorie'])){
            $res = "<article class='row'>";
            $res .= "<h2 class='title col s12 m12 l12'>".$this->data['categorie']->nom."</h2>";

            $res .= "<section class='col s12 m3 l3 row'>";
            $res .= "<div class='cat col s12 m12 l12 row'>";

            foreach($this->data['categories'] as $key => $t){

                $res .= "<a class='";
                    if($t->id == $this->data['categorie']->id)
                    $res .= "active";
                $res .= " cat-item col s3 m12 l12' href='".$router->urlFor('categorie')."?id=$t->id'>$t->nom</a>";
            }

            $res .= "</div></section>";

            

            $res .= "<section class='col s12 m9 l9 row'>";

            $res .= "<div class='col s12 m12 l12 row'>";
            $res .= "<a class='tri col offset-s4 offset-m4 offset-l4 s2 m2 l2' href='".$router->urlFor('categorie',['id' => $this->data['categorie']->id,'tri' => 'DESC'])."'>DESC</a>&nbsp;&nbsp;";
            $res .= "<a class='tri col s2 m2 l2' href='".$router->urlFor('categorie',['id' => $this->data['categorie']->id,'tri' => 'ASC'])."'>ASC</a>";
            $res .= "</div>";

                foreach($this->data['prestations'] as $key => $t){

                    if(isset($_SESSION['user_login']) && $_SESSION['access_level'] == 200){

                        $res .= "<div class='col s12 m6 l4 prestation row'>";
                        $res .= "<div class='row pres_info col s12 m12 l12'>";
                        $res .= "<span class='col s10 m10 l10'>$t->nom</span>";
                        $res .= "<p class='col s2 m2 l2'>$t->prix</p>";
                        $res .= "</div>";
                        $res .= "<a class='imgContainer' href='".$router->urlFor('prestation',['id' => $t->id])."'>";
                        $res .= "<img class='imgPres' src='$app_root/html/images/$t->img' /></a>";

                        $res .= "<div class='row pres_info col s12 m12 l12'>";
                        $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('suppPrestation')."?id=$t->id'>Supprimer</a>";
                        if($t->status == 1){
                        
                        $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('desacPrestation')."?id=$t->id'>Desactiver</a>";
                        }
                        else
                        {
                            $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('activerPrestation')."?id=$t->id'>Activer</a>";		
                        }
                    
                        $res .= "</div>";
                    $res .= "</div>";
        
                    }else{
                        if($t->status == 1){
                            $res .= "<div class='col s12 m6 l4 prestation row'>";
                            $res .= "<div class='row pres_info col s12 m12 l12'>";
                            $res .= "<span class='col s10 m10 l10'>$t->nom</span>";
                            $res .= "<p class='col s2 m2 l2'>$t->prix</p>";
                            $res .= "</div>";
                            $res .= "<a class='imgContainer' href='".$router->urlFor('prestation',['id' => $t->id])."'>";
                            $res .= "<img class='imgPres' src='$app_root/html/images/$t->img' /></a>";
                            if (isset($_SESSION['coffret'])) {
                                if($_SESSION['coffret_etat'] == 'en cours'){
                                    $res .= "<div class='row pres_info col s12 m12 l12'>";
                                    $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('addPrestationToCoffret')."?id=$t->id'>Ajouter</a>";
                                    $res .= "</div>";
                                } 
                            }
                            $res .= "</div>";
                        }
                    }
                }
        
                $res .= "</section>";
                $res .= "</article>";

            return $res;
            }else{
                return;
            }
    }

    private function renderPrestation(){

        $router = new \mf\router\Router(); 
        $app_root = (new \mf\utils\HttpRequest())->root; 

         if(!is_null($this->data['prestation'])){
            
            $res = "<article class='row'>";
            
            $res .= "<div class='pres_Detail col s12 m12 l12 row'>"; 
            $res .= "<div class='col s12 m4 l4'>"; 
                    $res .= "<p class='presDetail_item'>Nom : ".$this->data['prestation']->nom."</p>";
                    $res .= "<p class='presDetail_item'>Prix : ".$this->data['prestation']->prix."</p>";
                    $res .= "<span  class='presDetail_item'>Categorie :<a class='myCat' href='".$router->urlFor('categorie',['id' => $this->data['categorie']->id])."' class=''>".$this->data['categorie']->nom."</a></span>";
                    $res .= "<p class='presDetail_item'>Description : ".$this->data['prestation']->description."</p>";
            
                    if(isset($_SESSION['user_login']) && $_SESSION['access_level'] == 200){
                    $res .= "<div class='row pres_info col s12 m12 l12'>";
                        $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('suppPrestation')."?id=".$this->data['prestation']->id."'>Supprimer</a>";
                        if($this->data['prestation']->status == 1){
                        
                        $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('desacPrestation')."?id=".$this->data['prestation']->id."'>Desactiver</a>";
                        }
                        else
                        {
                            $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('activerPrestation')."?id=".$this->data['prestation']->id."'>Activer</a>";		
                        }
                    
                        $res .= "</div>";
                    }

                    if (isset($_SESSION['coffret'])) {
                        if($_SESSION['coffret_etat'] == 'en cours'){
                            $res .= "<div class='row pres_info col s12 m12 l12'>";
                            $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('addPrestationToCoffret')."?id=".$this->data['prestation']->id."'>Ajouter</a>";
                            $res .= "</div>";
                        } 
                    }

            $res .= "</div>";
            $res .= "<div class='col s12 m8 l8'>"; 
                    $res .= "<img class='imgPresDetail' src='$app_root/html/images/".$this->data['prestation']->img."' />";
                    $res .= "</div>";
            $res .= "</div>";
            
            $res .= "</article>";
                
            return $res;
         }else{
             return;
         }

    }
  
     
    

    private function renderViewSignup(){
        $router = new \mf\router\Router();

        $res = "<div class='row'>"; 
        $res .= "<div class='col s1 m4 l4'></div>"; 
        $res .= "<form id='tweet-form' class='form col s10 m4 l4 row' method='POST' action='".$router->urlFor('checksignup')."'>".       
        "<input class='input col s12 m12 l12' type='text' name='fullname' id='fullname' placeholder='full name' required /><br>".
        "<input class='input col s12 m12 l12' type='test' name='username' id='username' placeholder='username' required /><br>".
        "<input class='input col s12 m12 l12' type='password' name='password' id='password' placeholder='password' required /><br>".
        "<input class='input col s12 m12 l12' type='password' name='rpassword' id='rpassword' placeholder='retype password' required /><br>".  
        "<input class='input signupBtn col s12 m12 l12' type='submit' value='Sign up' id='signup' name='signup' /></form>";
        $res .= "<div class='col s1 m4 l4'></div>";
        $res .= "</div>";
        return $res;
    }

    private function renderViewLogin(){
        $router = new \mf\router\Router();
        $res = "<div class='row'>"; 
        $res .= "<div class='col s1 m4 l4'></div>"; 

        $res .= "<form id='tweet-form' class='form col s10 m4 l4 row' method='POST' action='".$router->urlFor('checkLogin')."'> ".       
        "<input class='input col s12 m12 l12' type='test' name='username' id='username' placeholder='username' required /><br>".
        "<input class='input col s12 m12 l12' type='password' name='password' id='password' placeholder='password' required /><br>".  
        "<input class='input signupBtn col s12 m12 l12' type='submit' value='Send' id='' name='send' /></form>";

        $res .= "<div class='col s1 m4 l4'></div>";
        $res .= "</div>";
        return $res;
    }

    public function renderAddCoffret(){

        $router = new \mf\router\Router();

        $res = "<div class='row'>"; 
        $res .= "<div class='col s1 m4 l4'></div>"; 

        $res .= "<form class='form col s10 m4 l4 row' action='".$router->urlFor('addCoffret')."' method='post'> ".
        "<input class='input col s12 m12 l12' type='text' name='nom' id='nom' placeholder='nom' required /><br>".
        "<input class='input col s12 m12 l12' type='date' name='date' id='date' required /><br>".
         "<input class='input signupBtn col s12 m12 l12' type='submit' value='Creer votre coffret' id='addCoffret' name='addCoffret' /></form>";
        
         $res .= "<div class='col s1 m4 l4'></div>";
         $res .= "</div>";
         return $res;
    }


        //------------->>>>>>>>>>>>>>>>>>>>>>>>>
        private function renderInsertCagnotte(){
            $router = new \mf\router\Router();
            $res = "<div class='row'>"; 
            $res .= "<div class='col s1 m4 l4'></div>"; 

            $res .= "<form class='form col s10 m4 l4 row' method='POST' action='".$router->urlFor('addCagnotte',['id' => $_GET['id']])."' enctype='multipart/form-data'> ".    
            "<input class='input col s12 m12 l12' type='text' name='prix' id='prix' placeholder='Prix' required /><br>". 
            "<input class='input col s12 m12 l12' type='text' name='nom' id='nom' placeholder='Nom' required /><br>".
            "<input class='input col s12 m12 l12' type='text' name='prenom' id='prenom' placeholder='Prenom' required /><br>".
            "<input class='input col s12 m12 l12' type='text' name='message' id='message' placeholder='message' required /><br>".
            "<input  class='input signupBtn col s12 m12 l12' type='submit' value='Valider' id='valider' name='valider' /></form>";

            $res .= "<div class='col s1 m4 l4'></div>";
            $res .= "</div>";

            return $res;
        }

    public function renderEditMessage(){

        $router = new \mf\router\Router();

        $res = "<div class='row'>"; 
        $res .= "<div class='col s1 m4 l4'></div>"; 

        $res .= "<form class='form col s10 m4 l4 row' action='".$router->urlFor('editMessage')."' method='post'> ".
        "<textarea class='input col s12 m12 l12' name='message' id='message' placeholder='message' required /></textarea><br>".
         "<input class='input signupBtn col s12 m12 l12' type='submit' value='Modifier le message' id='editMsg' name='editMsg' /></form>";
        
         $res .= "<div class='col s1 m4 l4'></div>";
         $res .= "</div>";
         return $res;
    }

    private function renderInsertPrest(){
        $router = new \mf\router\Router();
        $res = "<div class='row'>"; 
        $res .= "<div class='col s1 m4 l4'></div>"; 

        $res .= "<form class='form col s10 m4 l4 row' method='POST' action='".$router->urlFor('createPrestation')."' enctype='multipart/form-data'> ".    
        "<input class='input col s12 m12 l12' type='text' name='nom' id='nom' placeholder='Nom' required /><br>".
        "<input class='input col s12 m12 l12' type='text' name='description' id='description' placeholder='Description' required /><br>".
        "<input class='input col s12 m12 l12' type='text' name='prix' id='prix' placeholder='Prix' required /><br>". 
		"<select class='input col s12 m12 l12' name='type'>";
		foreach($this->data as $key => $t){

           $res .= "<option value='$t->id'>$t->nom</option>";
            
		}
			
        $res .= "</select><br>".
        "<input class='input col s12 m12 l12' type='file' name='file' id='file' required /><br>".
        "<input class='input signupBtn col s12 m12 l12' type='submit' value='Valider' id='valider' name='valider' /></form>";

        $res .= "<div class='col s1 m4 l4'></div>";
         $res .= "</div>";

        return $res;
    }


    
    

    private function renderProfilLogin(){
        $router = new \mf\router\Router();
         $app_root = (new \mf\utils\HttpRequest())->root; 
		
		if(isset($_SESSION['user_login'])){
            $res  = "<section class='row'>"; 
            $res  .= "<div class='col s12 m5 l5 row'>";

                    $res .= "<div class='profil col s12 m12 l12'>";
                    $res .= "<img class='imgProfil' src='$app_root/html/images/profil.png' >";
                    $res .= "<h1>".$this->data['user']->fullname."</h1>";
                    $res .= "<h1>".$this->data['user']->username."</h1>";
                    $res .= "<p class='backg'></p>";
                    $res .= "</div>";

            $res .= "</div>";

            $res  .= "<div class='col s12 m7 l7' row>";
            
                    foreach($this->data['coffrets'] as $key => $t){

                        
                        $res .= "<div class='col s12 m6 l4 ThecoffretS row'>";
						$res .= "<a class='cof_i col s12 m12 l12 row' href='".$router->urlFor('selectCoffret',['id' => $t->id])."'>";

                            $res .= "<div class='row coff_info col s12 m12 l12'>";
                            $res .= "<span class='col s6 m6 l6'>Nom : $t->nom</span>";
                            $res .= "<p class='col s6 m6 l6'>Prix : $t->prix</p>";
                            $res .= "<span class='col s6 m6 l6'>Etat : $t->etat</span>";
                            $res .= "<p class='col s6 m6 l6'>Date : $t->date</p>";
                            $res .= "</div>";

            $res .= "</a>";
                        $res .= "</div>";
                         
                     }
            $res .= "</div>";
            $res .= "</section>";
                    
		}
		return $res;        
    }

    private function renderCoffrets(){
        $router = new \mf\router\Router(); 
        $app_root = (new \mf\utils\HttpRequest())->root; 
        
        if(!is_null($this->data['coffret'])){

            $res = "<article class='row'>";

            $res .= "<section class='col s12 m3 l3 row'>";
            //valider supprimer payer
            $res .= "<div class='bnt-edit-Con col s12 m12 l12 row'>";
         
            $res .= "<a class='bnt-edit col s4 m4 l4' href='".$router->urlFor('suppCoffret',['id' => $this->data['coffret']->id])."'> supprimer </a>";

            if($this->data['coffret']->etat == 'en cours'){
                $checkValidity = false;

                foreach($this->data['prestations'] as $key => $t){
                    if($this->data['prestations'][0]->id_cat != $t->id_cat)
                        $checkValidity = true;
                }

                if($checkValidity == true)
                    $res .= "<a class='bnt-edit col s8 m8 l8' href='".$router->urlFor('validerCoffret',['id' => $this->data['coffret']->id])."'> Valider </a>";
                    $res .= "<a class='bnt-edit col s8 m8 l8' href='".$router->urlFor('formMessage',['id' => $this->data['coffret']->id])."'> editMessage </a>";
            }
//------------------------------>>>>>>>>>>>>>>>>>>>>>>>
            if($this->data['coffret']->etat == 'valider'){

                $res .= "<form class='form col s8 m8 l8 row' action='".$router->urlFor('payerCoffret',['id' => $this->data['coffret']->id])."' method='post'> ".
                    "<div class='col s6 m6 l6'>".
                    "<input class='bnt-edit col s12 m12 l12' type='submit' value='Payer' id='Payer' name='Payer' />".
                    "</div>".
                    "<div class='col s6 m6 l6'>".
                    "Classique <input class='col s12 m12 l12' type='radio' name='payer' value='classique' required />".
                    "Cagnotte <input class='col s12 m12 l12' type='radio' name='payer' value='cagnotte' required />".
                    "</div>".
                    "</form>";
                   
            }

            if($this->data['coffret']->etat == 'payer'){

                $res .= "<a class='bnt-edit col s8 m8 l8' href='".$router->urlFor('cadeau',['id' => $this->data['coffret']->url])."'> Url cadeau </a>";

            }

            if($this->data['coffret']->etat == 'cagnotte'){

                $res .= "<a class='bnt-edit col s8 m8 l8' href='".$router->urlFor('formCagnotte',['id' => $this->data['coffret']->id])."'> Url cagnotte </a>";

            }

            $res .= "</div>";
            $res .= "</section>";

            $res .= "<section class='col s12 m9 l9 row'>";
            $res .= "</section>";

            $res .= "<section class='col s12 m3 l3 row'>";
            
            $res .= "<div id='cofNav' class='cat col s12 m12 l12 row'>";

            foreach($this->data['coffrets'] as $key => $t){

                $res .= "<a class='";
                    if($t->id == $this->data['coffret']->id)
                    $res .= "active";
                $res .= " cat-item col s3 m12 l12' href='".$router->urlFor('selectCoffret',['id' => $t->id])."'>$t->nom</a>";
            }

            $res .= "</div></section>";

            $res .= "<section class='col s12 m9 l9 row'>";
            

            $res .= "<div class='coff_infoS col s12 m12 l12 row'>";
            
            $res .= "<p class='detailCofInfo col s4 m4 l4' >Prix : ".$this->data['coffret']->prix."</p>";
            $res .= "<p class='detailCofInfo col s4 m4 l4'>Etat : ".$this->data['coffret']->etat."</p>";
            $res .= "<p class='detailCofInfo col s4 m4 l4'>Date : ".$this->data['coffret']->date."</p>";
            
            $res .= "<p class='detailCofInfo col s12 m12 l12'>Message : ".$this->data['coffret']->message."</p>";
            
            $res .= "</div>";

            $res .= "<div class='col s12 m12 l12 row'>";
            $res .= "<a class='tri col offset-s4 offset-m4 offset-l4 s2 m2 l2' href='".$router->urlFor('coffrets',['id' => $this->data['coffret']->id,'tri' => 'DESC'])."'>DESC</a>&nbsp;&nbsp;";
            $res .= "<a class='tri col s2 m2 l2' href='".$router->urlFor('coffrets',['id' => $this->data['coffret']->id,'tri' => 'ASC'])."'>ASC</a>";
            $res .= "</div>";

            foreach($this->data['prestations'] as $key => $t){
                
                $res .= "<div class='col s12 m6 l4 prestation row'>";
                            $res .= "<div class='row pres_info col s12 m12 l12'>";
                            $res .= "<span class='col s10 m10 l10'>$t->nom</span>";
                            $res .= "<p class='col s2 m2 l2'>$t->prix</p>";
                            $res .= "</div>";
                            $res .= "<a class='imgContainer' href='".$router->urlFor('prestation',['id' => $t->id])."'>";
                            $res .= "<img class='imgPres' src='$app_root/html/images/$t->img' /></a>";
                            $res .= "<div class='row pres_info col s12 m12 l12'>";

                             if($this->data['coffret']->etat == 'en cours')
                                $res .= "<a class='presBtn col s6 m6 l6' href='".$router->urlFor('suppPrestationUser')."?id=$t->id'>Supprimer$t->etat</a>";

                            $res .= "</div>";
                            $res .= "</div>";
            
            }

            $res .= "</section>";
            $res .= "</article>";
        
        return $res;

        }
        
    }

    private function renderCadeau(){
        $router = new \mf\router\Router();
        $app_root = (new \mf\utils\HttpRequest())->root; 
         
        $date = date('Y-m-d');
        $res = "";  
            if($this->data['coffret']->date <= $date){

                $res = "";       
            
            $res .= "<div class='coff_infoS col s12 m12 l12 row'>";
            
            $res .= "<p class='detailCofInfo col s12 m12 l12'>Message coffret :".$this->data['coffret']->message."</p>";

            if(isset($this->data['cagnottes'])){

                foreach($this->data['cagnottes'] as $key => $t){

            $res .= "<p class='detailCofInfo col s12 m12 l12'>Message de $t->nom $t->prenom :".$t->message."</p>";                    

                }

            }

            $res .= "</div>";   
    
                $res .= "<section class='col s12 m9 l9 row'>";
    
    
    
                foreach($this->data['prestations'] as $key => $t){
                    
                    $res .= "<div class='col s12 m6 l4 prestation row'>";
                                $res .= "<div class='row pres_info col s12 m12 l12'>";
                                $res .= "<span class='col s10 m10 l10'>$t->nom</span>";
                                $res .= "<p class='col s2 m2 l2'>$t->prix</p>";
                                $res .= "</div>";
                                $res .= "<a class='imgContainer' >";
                                $res .= "<img class='imgPres' src='$app_root/html/images/$t->img' /></a>";
                                $res .= "</div>";
                
                }
                $res .= "</section>"; 


            }else {

                $ts1 = strtotime($date);
                $ts2 = strtotime($this->data['coffret']->date);

                $dateRemain = $ts2 - $ts1;

                $dt1 = new \DateTime("@0");
                $dt2 = new \DateTime("@$dateRemain");
                $dateRemain =  $dt1->diff($dt2)->format('%a days, %h hours, %i minutes and %s seconds');

                $res .= "<div class='centerIt row col s12 m12 l12'>il vous reste : ".$dateRemain."</div>";

            }

                              
            
             return $res;        
        }


    private function renderBottomMenu(){
        $router = new \mf\router\Router();
        $res = "";
        return $res;
    }

    private function renderTopMenu(){
        $router = new \mf\router\Router();
        $res = "";
        
        if(isset($_SESSION['user_login'])){
            if($_SESSION['access_level'] == 200){
                $res .= "<a class='nav-item col s3 m3 l3' href='".$router->urlFor('prestations')."'>Prestations</a>";
                $res .= "<a class='nav-item col s2 m2 l2' href='".$router->urlFor('profil')."'>Profil</a>";
                $res .= "<a class='nav-item col s3 m2 l2' href='".$router->urlFor('formPrestation')."'>Ajouter Prestation</a>";
                $res .= "<a class='nav-item col s2 m2 l2' href='".$router->urlFor('logout')."'>LOGOUT</a>";
                $res .= "<a class='login-item col s2 m2 l2' > ".$_SESSION['user_login']." </a>";
            }else{
                $res .= "<a class='nav-item col s2 m2 l2' href='".$router->urlFor('prestations')."'>Prestations</a>";
                $res .= "<a class='nav-item col s2 m2 l2' href='".$router->urlFor('profil')."'>Profil</a>";
                $res .= "<a class='nav-item col s2 m2 l2' href='".$router->urlFor('formCoffret')."' class=''>Ajouter Coffret</a>";
                $res .= "<a class='nav-item col s2 m2 l2' href='".$router->urlFor('coffrets')."'>Voir Coffrets</a>";
                $res .= "<a class='nav-item col s2 m2 l2' href='".$router->urlFor('logout')."'>LOGOUT</a>";
                $res .= "<a class='login-item col s2 m2 l2' > ".$_SESSION['user_login']." </a>";
            }
        }else{
            $res .= "<a class='nav-item col s4 m4 l4' href='".$router->urlFor('login')."'>LOGIN</a>";
            $res .= "<a class='nav-item col s4 m4 l4' href='".$router->urlFor('signup')."'>SIGNUP</a>";
        }
         
        return $res;
    }

    
    protected function renderBody($selector=null){

        /*
         * voire la classe AbstractView
         * 
         */
         $http_req = new \mf\utils\HttpRequest();

         $res = "";

         $res .= "<header class='row'>".$this->renderHeader();
         
         
         $res .= "<nav  class='col s12 m12 l12 row' id='nav-menu'>";

         $res .= $this->renderTopMenu();

         $res .= "</nav>";

            if(isset($_SESSION['coffret'])){
                $res .= "<div id='coffretBar' class='col s12 m12 l12 row' >";
                $res .= $this->renderSelectedCoffret();
                $res .= "</div>";
            }
            

         $res .= "</header>";

         $res .= "<section>";


         switch ($selector) {
                case 'coffrets':
                    $res .= $this->renderCoffrets();
                    break;
                case 'coffretDetail':
                    $res .= $this->renderCoffretDetail();
                    break;

            
                case 'prestations':
                    if(isset($_SESSION['user_login'])){
                        $res .= $this->renderPrestations();
                    }else{
                        $res .= $this->renderViewLogin();
                    }
                    break;

                case 'categoriePrestations':
                    $res .= $this->renderCategoriePrestations();
                    break;
                
                case 'prestation':
                    $res .= $this->renderPrestation();
                    break;
                
                case 'login':
                    $res .= $this->renderViewLogin();
                    break;
                
                case 'signup':
                    $res .= $this->renderViewSignup();
                    break;
               
                case 'formPrestation':
                    $res .= $this->renderInsertPrest();
                    break;
                         
                case 'addCoffret':
                    $res .= $this->renderAddCoffret();
                    break;
                   
                case 'profil':
                    $res .= $this->renderProfilLogin();
                    break;

                case 'cadeau':
                    $res .= $this->renderCadeau();
                    break;

                case 'formMessage':
                    $res .= $this->renderEditMessage();
                    break;

                case 'formCagnotte':
                    $res .= $this->renderInsertCagnotte();
                    break;
        }


        if(isset($_SESSION['user_login']))
         $res .= $this->renderBottomMenu();

         

         $res .= "</section>";

         $res .= "<footer class='theme-backcolor1'>".$this->renderFooter()."</footer>";

         return $res;
    }


    
}

?>