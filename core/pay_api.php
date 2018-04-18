<?php

//http://www.wikistuce.info/doku.php/articles/mise_en_place_d_un_paiement_paypal_sur_site_marchand


/**
 * Trame Paypal
 */
function construit_url_paypal(){
    $api_paypal = 'https://api-3t.sandbox.paypal.com/nvp?'; // Site de l'API PayPal. 
    $version = 111; // Version de l'API
    
    $user = 'christophe_api1.machris.fr'; // Utilisateur API
    $pass = '1395263593'; // Mot de passe API
    $signature = 'AfSJlyFm2a1eLme31TItMYmHLSezA7YohfC96QDZg3VliGLze-LGeRO9'; // Signature de l'API
    $api_paypal = $api_paypal.'VERSION='.$version.'&USER='.$user.'&PWD='.$pass.'&SIGNATURE='.$signature; // Ajoute tous les paramètres
    return  $api_paypal; // Renvoie la chaîne contenant tous nos paramètres.
}

/**
 * Transform le resultat de la requete en tableau
 */
function recup_param_paypal($resultat_paypal)
{
    $liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres
    foreach($liste_parametres as $param_paypal) // Pour chaque paramètre
    {
        list($nom, $valeur) = explode("=", $param_paypal); // Sépare le nom et la valeur
        $liste_param_paypal[$nom]=urldecode($valeur); // Crée l'array final
    }
    return $liste_param_paypal; // Retourne l'array
}

/**
 * Requete SetExpressCheckout
 */
function paypal_SetExpressCheckout() {
    $requete = construit_url_paypal(); // Construit les options de base
    // La fonction urlencode permet d'encoder au format URL les espaces, slash, deux points, etc.)
    $requete = $requete."&METHOD=SetExpressCheckout".
                "&CANCELURL=".urlencode("http://assotstd.com/test?cancel").
                "&RETURNURL=".urlencode("http://assotstd.com/test?return").
                "&AMT=10.0".
                "&CURRENCYCODE=EUR".
                "&DESC=".urlencode("Magnifique oeuvre d'art (que mon fils de 3 ans a peint.)").
                "&LOCALECODE=FR".
                "&HDRIMG=".urlencode("http://www.siteduzero.com/Templates/images/designs/2/logo_sdz_fr.png");
    
    $ch = curl_init($requete);
        
    // ignorer la verification du certificat SSL. Si cette option est a 1, une erreur affichera que la verification du certificat SSL a échoue, et rien ne sera retourne. 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    // Retourne directement le transfert sous forme de chaine de la valeur retournee par curl_exec() au lieu de l'afficher directement. 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $resultat_paypal = curl_exec($ch);
    
    if (!$resultat_paypal){
        echo "<p>Erreur</p><p>".curl_error($ch)."</p>";
    } else  {
        $liste_param_paypal=recup_param_paypal($resultat_paypal);
        if ($liste_param_paypal['ACK'] == 'Success'){
            // Redirige le visiteur sur le site de PayPal
            header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);
            exit();
    
        } else {
            echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
        }
    }
    curl_close($ch);    
}

/**
 * Requete ExpressCheckout
 */
function paypal_DoExpressCheckoutPayment() {
    $requete = construit_url_paypal();
    $requete .= "&METHOD=DoExpressCheckoutPayment".
    			"&TOKEN=".htmlentities($_GET['token'], ENT_QUOTES).
    			"&AMT=10.0".
    			"&CURRENCYCODE=EUR".
    			"&PayerID=".htmlentities($_GET['PayerID'], ENT_QUOTES).
    			"&PAYMENTACTION=sale";
    
    $ch = curl_init($requete);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $resultat_paypal = curl_exec($ch);
    
    if (!$resultat_paypal)	{
        echo "<p>Erreur</p><p>".curl_error($ch)."</p>";

    } else {
    	$liste_param_paypal = recup_param_paypal($resultat_paypal);
    	
    	// On affiche tous les paramètres afin d'avoir un aperçu global des valeurs exploitables (pour vos traitements). Une fois que votre page sera comme vous le voulez, supprimez ces 3 lignes. Les visiteurs n'auront aucune raison de voir ces valeurs s'afficher.
    	echo "<pre>";
    	print_r($liste_param_paypal);
    	echo "</pre>";
    	
    	if ($liste_param_paypal['ACK'] == 'Success')
    	{
    		echo "<h1>Youpii, le paiement a été effectué</h1>";
    	}
    	else {
    	    echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
   	    }
    }
    curl_close($ch);
}

/**
 * Request GetExpressCheckoutDetails
 */
function paypal_GetExpressCheckoutDetails() {
    $requete = construit_url_paypal();
    $requete = $requete."&METHOD=GetExpressCheckoutDetails".
    			"&TOKEN=".htmlentities($_GET['token'], ENT_QUOTES); // Ajoute le jeton
    
    $ch = curl_init($requete);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $resultat_paypal = curl_exec($ch);
    
    if (!$resultat_paypal) {
        echo "<p>Erreur</p><p>".curl_error($ch)."</p>";

    } else {
    	$liste_param_paypal = recup_param_paypal($resultat_paypal);
    	
    	echo "<pre>";
    	print_r($liste_param_paypal);
    	echo "</pre>";
    }
    curl_close($ch);    
}

/**
 * 
 */
function paypal_begin() {
   return '<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">'."\n";

}
function paypal_end() {
   return '</form>'."\n";
}
/**
 * Cree un bouton pour panier
 */
function paypal_btn_add_panier($name) {
    $ret .='<input type="hidden" name="cmd" value="_cart">'."\n";
    $ret .='<input type="hidden" name="business" value="SV3NR9GXWNYC2">'."\n";
    $ret .='<input type="hidden" name="item_name" value="'.htmlentities($name).'">'."\n";
    
    $ret.='<table>'."\n";
    $ret.='<tr><td><input type="hidden" name="on0" value="formats">formats</td></tr>';
    $ret.='<tr><td><select name="os0">'."\n";
    $ret.='<option value="tirage 30x40">tirage 30x40 &euro;15,00 EUR</option>'."\n";
    $ret.='<option value="30x40 contre coll&eacute; (carton)">30x40 contre coll&eacute; (carton) &euro;30,00 EUR</option>'."\n";
    $ret.='</select> </td></tr>'."\n";
    $ret.='</table>'."\n";
    $ret .='<input type="hidden" name="option_select0" value="tirage 30x40">'."\n";
    $ret .='<input type="hidden" name="option_amount0" value="15">'."\n";
    $ret .='<input type="hidden" name="option_select1" value="30x40 contre coll&eacute; (carton)">'."\n";
    $ret .='<input type="hidden" name="option_amount1" value="30">'."\n";
    $ret .='<input type="hidden" name="currency_code" value="EUR">'."\n";

    $ret .='<input type="image" src="https://www.sandbox.paypal.com/fr_FR/FR/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it s fast, free and secure!">'."\n";
    $ret .='<input type="hidden" name="add" value="1">'."\n";
    return $ret;
}


function paypal_buy_photos() {
        
        $photo = $_GET['buy'];
        $background= 'gallery_bkg.gif';
        $titre     = 'titre_5';
        $submenu  = "\n";
        $droite    .= '<img name="affiche" src="images/affiches/affiche expo-vente-mini.jpg" width="150" height="254" id="index_r1_c17" alt="" />';
        $contenu   = paypal_begin();

        $contenu   .= '<script>$(document).ready(function() {'."\n";
        $contenu   .= '$(".fancybox").fancybox({'."\n";
        $contenu   .= "        openEffect	: 'elastic',\n";
        $contenu   .= "        closeEffect	: 'elastic',\n";
        $contenu   .= "        helpers : {\n";
        $contenu   .= "            title : {\n";
        $contenu   .= "                type : 'inside'\n";
        $contenu   .= "            }\n";
        $contenu   .= "        }\n";
        $contenu   .= "    });\n";
        $contenu   .= "});\n";
        $contenu   .= '</script>'."\n";
        $contenu   .= '<div class="gal_grid"> '."\n";

        $contenu   .= '<p>'.pathinfo($photo)['filename'].'</p>';
        $contenu   .= '    <div><a class="fancybox" rel="group" href="elFinder-2.1.37/files/Exposition Espace Renoir/'.$photo.'"><img src="elFinder-2.1.37/files/Exposition Espace Renoir/miniatures/'.$photo.'" alt="" /></a></div>'."\n";
        $contenu   .= paypal_btn_add_panier($photo);
        $contenu   .= '</div>'."\n";
        

        $contenu   .= paypal_end();

        $contenu   .= '<br/><br/><br/><a href="index?page=302">Retour &agrave; la galerie</a>'."\n";
        
        
        $body=body($background,$titre,$submenu,$contenu,$droite,0);
        echo page(entete(),$body);   
}

?>
