<?php
session_start();

require_once("core/base.php");
//include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
include_once ('./securimage/securimage.php');
include_once("core/config.php");

class contact extends component {

    private $err_mail='';
    private $err_msg='';
    private $err_capcha='';
    private $message='';

    function display(){
        
        $this->execute();
        
        $ret = "<div>$this->message</div>\n";
        $ret ='  <div class="contact" style="display:block">'."\n";
        $ret .='  <div style="display:inline-block;width:520px;">'."\n";
        $ret .='<form method="post" action="index?page='.$this->id.'">'."\n";
        $ret .='  <p>Nom <input type="text" name="nom" size="30" /></p>'."\n";
        $ret .='  <p>email <span style="color:#962121;">*</span>:<input style="display:inline-block" type="text" name="email" size="30" /><span style="color:#962121;font-weight:bold;">'.$this->err_mail.'</span></p>'."\n";
        $ret .='  <p>Message <span style="color:#962121;">*</span>:</p>'."\n";
        $ret .='  <textarea name="message" cols="60" rows="10"></textarea>'."\n";
        $ret .='  <p style="color:#962121;font-weight:bold;">'.$this->err_msg.'</p>'."\n";
        $ret .='  <p>'."\n";
        $ret .='    Merci de taper la s&eacute;quence suivante pour v&eacute;rifier que vous &ecirc;tes bien un humain :<br/>'."\n";
        $ret .='    <img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" />'."\n";
        $ret .='      <input type="text" name="captcha_code" size="10" maxlength="6" /><span style="color:#962121;font-weight:bold;">'.$this->err_capcha.'</span><br/>'."\n";
        $ret .="      <a href=\"#\" onclick=\"document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false\">[ Reg&eacute;n&eacute;rer l'image ]</a>\n";
    	    
        $ret .='    </p>'."\n";
        $ret .='    <p><input type="submit" name="submit" value="Envoyer" /></p>'."\n";
        $ret .='  </form>'."\n";
        $ret .='  </div>'."\n";
        //$ret .='  <div style="vertical-align:bottom;display:inline-block;width:150px;">';
        //$ret.='<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HPMZKZHLF6KQW" ><img src="images/tirelire.png" /></a>';
        //$ret.='</div>'."\n";
        
        $paypal  = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
        $paypal  .= '<input type="hidden" name="cmd" value="_s-xclick">';
        $paypal  .= '<input type="hidden" name="hosted_button_id" value="APZTQ7VJR3TQW">';
        $paypal  .= '<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">';
        $paypal  .= '<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">';
        $paypal  .= "</form>\n";
        
        $ret    .= "<p><strong>Vous pouvez aussi faire un don par ici</strong></p>\n".$paypal;
        

        $ret .='  </div>'."\n";
        
        return $ret;

    }
    
  	function execute() { 
	    $securimage = new Securimage();
        $nombreErreur = 0;
        global $email_destinataire;
	
        // S'il y des données de postées
        if ($_SERVER['REQUEST_METHOD']=='POST') {
            // Code PHP pour traiter l'envoi de l'email
             
            // Définit toutes les erreurs possibles
            if (!isset($_POST['email'])) { // Si la variable "email" du formulaire n'existe pas (il y a un problème)
                $nombreErreur++; // On incrémente la variable qui compte les erreurs
                $this->err_mail='Il y a un probl&egrave;me avec la variable "email".';
            } else { // Sinon, cela signifie que la variable existe (c'est normal)
                if (empty($_POST['email'])) { // Si la variable est vide
                    $nombreErreur++; // On incrémente la variable qui compte les erreurs
                    //$this->assign("erreur2", '<p>Vous avez oubli&eacute; de donner votre email.</p>');
                    $this->err_mail= 'Vous avez oubli&eacute; de donner votre email.';
                } else {
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $nombreErreur++; // On incrémente la variable qui compte les erreurs
                        $this->err_mail='Cet email ne ressemble pas un email.';
                    }
                }
            }
             
            if (!isset($_POST['message'])) {
                $nombreErreur++;
                $this->err_msg='Il y a un probl&egrave;me avec la variable "message".';
            } else {
                if (empty($_POST['message'])) {
                    $nombreErreur++;
                    $this->err_msg='Vous avez oubli&eacute; de donner un message.';
                } 
            } 
        
            if ($securimage->check($_POST['captcha_code']) == false) {
                $nombreErreur++;
        	    $this->err_capcha="Erreur Capcha";
        	}
                 
            if ($nombreErreur==0) { // S'il n'y a pas d'erreur
                // Ici il faut ajouter tout le code pour envoyer l'email.
                 // (1) Code PHP pour traiter l'envoi de l'email
                 
                // Récupération des variables et sécurisation des données
                $nom = htmlentities($_POST['nom']); // htmlentities() convertit des caractères "spéciaux" en équivalent HTML
                $email = htmlentities($_POST['email']);
                $message = htmlentities($_POST['message']);
                 
                // Variables concernant l'email
                 
                $destinataire = $email_destinataire; 
                $sujet = 'Titre du message'; // Titre de l'email
                $contenu = '<html><head><title>Titre du message</title></head><body>';
                $contenu .= '<p>Bonjour, vous avez reçu un message &agrave; partir de votre site web.</p>';
                $contenu .= '<p><strong>Nom</strong>: '.$nom.'</p>';
                $contenu .= '<p><strong>Email</strong>: '.$email.'</p>';
                $contenu .= '<p><strong>Message</strong>: '.$message.'</p>';
                $contenu .= '</body></html>'; // Contenu du message de l'email (en XHTML)
                 
                // Pour envoyer un email HTML, l'en-tête Content-type doit être défini
                $headers = 'MIME-Version: 1.0'."\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                 
                // Envoyer l'email
                mail($destinataire, $sujet, $contenu, $headers); // Fonction principale qui envoi l'email
                $this->message='<h2>Message envoy&eacute;!</h2>';
                // (2) Fin du code pour traiter l'envoi de l'email
            }
        }        
    }
}


$contacts = array(new contact(200,5,'contact'));

?>
