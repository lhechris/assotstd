<?php
session_start();
require_once("core/config.php");
require_once("core/article.php");
require_once("core/gallery.php");
require_once("administration/gallery.php");

class ClAdmin {

    /* 
     * Affiche la page d'edition d une page 
     */
    private function affiche_edit() {
        
        global $articles;
        
        if (isset($_POST["cancel"])) {   
            return $this->affiche_main();
        }
        
        $id=intval($_GET['content']); 
        
        //recherche l'article
        $article=null;
        foreach ($articles as $art) {
            if ($art->id == $id) {
                $article=$art;
            }  
        }
        
        if ($article==null) {
            return $this->affiche_main();
        }
        
        
        $content=file_get_contents("datas/".$article->name.".txt");      

        if (isset($_POST["save"])) { 
            file_put_contents("datas/".$article->name.".txt",$_POST["elm1"]); 
                  $content=$_POST["elm1"];
        }

        return admin_page(admin_entete_edit(),admin_body_edit($content,$article->id,$article->name));

    }

    /**
     *
     */
     private function affiche_login() {
         session_destroy();
         
         $ret  = "  <div id=\"logging\">\n";
         $ret  .="    <div>\n";
         $ret .= "      <p>Identifiant</p>\n";
         $ret .= "      <input type=\"text\" name=\"login\" />\n";
         $ret .= "    </div>\n";
         $ret .= "    <div>\n";
         $ret .= "      <p>mot de passe</p>  \n";
         $ret .= "      <input type=\"password\" name=\"password\" />\n";
         $ret .= "    </div>\n";
         $ret .= "    <div>  \n";
         $ret .= "      <input type=\"submit\" name=\"ok\" value=\"se connecter\" />\n";
         $ret .= "    </div>\n";
         $ret .= "  </div>\n";
         
         return admin_page(admin_entete(),admin_body($ret));
         
     }
    
    private function affiche_gallery() {
        return admin_page(admin_gallery_entete(),admin_gallery_body());
    }
    
    private function affiche_list_galeries() {
        $obj=new ClGalleryList();
        return admin_page(admin_entete(),admin_body($obj->get_content()));
        
    }

    
    private function affiche_main() {
        global $articles;
        global $email_destinataire;
        $ret = '  <div>'."\n";
        $ret .= '    <p>Liste des articles</p>'."\n";
        $ret .= '    <p>Cliquer dessus pour les modifier</p>'."\n";
        $ret .= '    <ul>'."\n";
        foreach($articles as $art) {
            $ret .= '    <li>';
            $ret .= "<a href=\"admin?content=$art->id\">$art->name</a>";
            $ret .= "</li>\n";
        }
        $ret .= '    </ul>'."\n";
        $ret .= '  </div>'."\n";
        //$ret .= '  <div><a href="admin?gallery">Les galeries photos</a></div>'."\n";
		$ret .= '  <div><a href="elFinder-2.1.37/elfinder.php">Les galeries photos</a></div>'."\n";
        $ret .= '  <div>'."\n";
        $ret .= "    <p>Email contact : <input type=\"text\" name=\"email\" value=\"$email_destinataire\"/></p>\n";
        $ret .= '  </div>'."\n";
        $ret .= '  <div><input type="submit" name="save" value="enregistrer" /></div>'."\n";
        $ret .= '  <div><a href="admin?logout">se d&eacute;connecter</a></div>'."\n";
        $ret .= '</div>'."\n";
        
        return admin_page(admin_entete(),admin_body($ret));

    }
    
    /**
     *
     */
     private function save() {
         global $email_destinataire;
         if (isset($_POST['email'])){
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $email_destinataire=htmlentities($_POST['email']);
            }            
         }
         file_put_contents("core/config.php","<?php\n".'$email_destinataire="'.$email_destinataire.'";'."\n?>");
     }
    
    /**
     *
     */
    private function islogged() {
        if (isset($_SESSION['logged'])) {
            return true;
        } else if ((isset($_POST['login'])) && (isset($_POST['password']))){
            $login=htmlentities($_POST['login']);
            $passwd=htmlentities($_POST['password']);
            if (($login=="assotstd") && ($passwd=="solidarity")) {
                $_SESSION['logged']="yes";
                return true;
            } else {
                return false;
            }
        } else {            
            return false;
        }
    }

    /**
     * Affiche
     */
    function display() {
        
        if (($this->islogged()==false)||(isset ($_GET['logout'])))  {
            return $this->affiche_login();
        
        } else if (isset ($_GET['content'])) {
            return $this->affiche_edit();

        } else if (isset ($_GET['gallery'])) {
            return $this->affiche_gallery();

        } else if (isset ($_GET['gallery_list'])) {
            return $this->affiche_list_galeries();

        } else if (isset ($_POST['save'])) {
            $this->save();
            return $this->affiche_main();
            
        } else {
            return $this->affiche_main();
        }
                
    }


}

function admin_page($head,$body) {
    $ret  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n";
    $ret .= '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
    $ret .= $head;
    $ret .= $body; 
    $ret .= "</html>\n";
    return $ret;
   
}

function admin_entete_edit() {
    $ret  = "<head>\n";
    $ret .= "  <title>Tous semblables tous diff&eacute;rents</title>\n";
    $ret .= "  <!-- Load jQuery -->\n";
    $ret .= '  <script type="text/javascript" src="http://www.google.com/jsapi"></script>'."\n";
    $ret .= '  <script type="text/javascript">'."\n";
    $ret .= '  	google.load("jquery", "1");'."\n";
    $ret .= "  </script>\n\n";

    $ret .= "  <!-- Load TinyMCE -->\n";
    $ret .= '  <script type="text/javascript" src="tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>'."\n";
    $ret .= '  <script type="text/javascript">'."\n";
    $ret .= "  	$().ready(function() {\n";
    $ret .= "  		$('textarea.tinymce').tinymce({\n";
    $ret .= "  			// Location of TinyMCE script\n";
    $ret .= "  			script_url : 'tinymce/jscripts/tiny_mce/tiny_mce.js',\n";
    $ret .= "  			// General options\n";
    $ret .= "  			theme : \"advanced\",\n";
    $ret .= '  			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",'."\n\n";

    $ret .= "  			// Theme options\n";
    $ret .= '  			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",'."\n";
    $ret .= '  			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",'."\n";
    $ret .= '  			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",'."\n";
    $ret .= '  			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",'."\n";
    $ret .= '  			theme_advanced_toolbar_location : "top",'."\n";
    $ret .= '  			theme_advanced_toolbar_align : "left",'."\n";
    $ret .= '  			theme_advanced_statusbar_location : "bottom",'."\n";
    $ret .= '  			theme_advanced_resizing : true,'."\n\n";

    $ret .= '  			content_css : "css/admin_edit.css",'."\n\n";

    $ret .= "  			// Drop lists for link/image/media/template dialogs\n";
    $ret .= '  			template_external_list_url : "lists/template_list.js",'."\n";
    $ret .= '  			external_link_list_url : "lists/link_list.js",'."\n";
    $ret .= '  			external_image_list_url : "lists/image_list.js",'."\n";
    $ret .= '  			media_external_list_url : "lists/media_list.js",'."\n\n";

    $ret .= "  			// Replace values for the template plugin\n";
    $ret .= "  			template_replace_values : {\n";
    $ret .= '  				username : "Some User",'."\n";
    $ret .= '  				staffid : "991234"'."\n";
    $ret .= "  			}\n";
    $ret .= "  		});\n";
    $ret .= "  	});\n";
    $ret .= "  </script>\n";
    $ret .= "  <!-- /TinyMCE -->\n\n";

    $ret .= "  </head>\n";
    return $ret;
       
}

function admin_entete() {
    $ret  = '<head>'."\n";
    $ret .= '  <title>Tous semblables tous diff&eacute;rents</title>'."\n";
    $ret .= '  <link href="css/admin.css" rel="stylesheet" media="all" type="text/css" />'."\n";
    $ret .= '</head>'."\n";
    return $ret;
}

function admin_body($content) {
    $ret = "<body>\n";
    $ret .= '<form method="post" action="admin">'."\n";
  /*  $ret .= '<div id="menu">'."\n";
    $ret .= '  <ul id="onglets">'."\n";
    $ret .= '    <li class="active"><a href="admin"> Articles </a></li>'."\n";
    $ret .= '    <li><a href="admin?gals"> Galeries </a></li>'."\n";
    $ret .= "  </ul>\n";*/
    $ret .= "</div>\n";
    $ret .= '<div id="main">'."\n";
    $ret .= $content;
    $ret .= "</div>\n";
    $ret .= '</form>'."\n";
    $ret .= '</body>'."\n";
    return $ret;
}

function admin_body_edit($content,$id,$pagename) {
    $ret = "<body>\n";

    $ret .= "  <form method=\"post\" action=\"admin?content=".strval($id)."\">\n";
    $ret .= "  	<div>\n";
    $ret .= "  		<h3>Tous semblables tous diff&eacute;rents : Administration</h3>\n";
    $ret .= "      <p>Modification de la page $pagename</p>\n";
    $ret .= "  		<div>\n";
    $ret .= '  			<textarea id="elm1" name="elm1" rows="5" cols="150" style="width: 70%" class="tinymce">';
    $ret .= $content;
    $ret .= "</textarea>\n";
    $ret .= "  		</div>\n";

    $ret .= "  		<!-- Some integration calls -->\n";
    $ret .= "  <!--		<a href=\"javascript:;\" onclick=\"$('#elm1').tinymce().show();return false;\">[Show]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"$('#elm1').tinymce().hide();return false;\">[Hide]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"$('#elm1').tinymce().execCommand('Bold');return false;\">[Bold]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"alert($('#elm1').html());return false;\">[Get contents]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"alert($('#elm1').tinymce().selection.getContent());return false;\">[Get selected HTML]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"alert($('#elm1').tinymce().selection.getContent({format : 'text'}));return false;\">[Get selected text]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"alert($('#elm1').tinymce().selection.getNode().nodeName);return false;\">[Get selected element]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"$('#elm1').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');return false;\">[Insert HTML]</a>\n";
    $ret .= "  		<a href=\"javascript:;\" onclick=\"$('#elm1').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');return false;\">[Replace selection]</a>-->\n";

    $ret .= "  		<br />\n";
    $ret .= '  		<input type="submit" name="save" value="sauvegarder" />'."\n";
    $ret .= '  		<input type="submit" name="cancel" value="fermer" />'."\n";
    $ret .= "  	</div>\n";
    $ret .= "  </form>\n";
    $ret .= '  <script type="text/javascript">'."\n";
    $ret .= "  if (document.location.protocol == 'file:') {\n";
    $ret .= '  	alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");'."\n";
    $ret .= "  }\n";
    $ret .= "  </script>\n";

    $ret .= "  </body>\n";
    return $ret;
    
}


function admin_gallery_entete() {
    $ret = "<head>\n";
	$ret .= '	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'."\n";
    $ret .= '  <title>Tous semblables tous diff&eacute;rents</title>'."\n";

	/*$ret .= "	<!-- jQuery and jQuery UI (REQUIRED) -->\n";
	$ret .= '	<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">'."\n";
	$ret .= '	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>'."\n";
	$ret .= '	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>'."\n";

	$ret .= "	<!-- elFinder CSS (REQUIRED) -->\n";
	$ret .= '	<link rel="stylesheet" type="text/css" media="screen" href="elFinder-2.1.37/css/elfinder.min.css">'."\n";
	$ret .= '	<link rel="stylesheet" type="text/css" media="screen" href="elFinder-2.1.37/css/theme.css">'."\n";

	$ret .= "	<!-- elFinder JS (REQUIRED) -->\n";
	$ret .= '	<script type="text/javascript" src="elFinder-2.1.37/js/elfinder.min.js"></script>'."\n";

	$ret .= "	<!-- elFinder translation (OPTIONAL) -->\n";
	$ret .= '	<script type="text/javascript" src="elFinder-2.1.37/js/i18n/elfinder.fr.js"></script>'."\n";

	$ret .= "	<!-- elFinder initialization (REQUIRED) -->\n";
	$ret .= '	<script type="text/javascript" charset="utf-8">'."\n";
	$ret .= "		$().ready(function() {\n";
	$ret .= "			var elf = $('#elfinder').elfinder({\n";
	$ret .= "				url : '../elFinder-2.1.37/php/connector.php',  // connector URL (REQUIRED)\n";
	$ret .= "				lang: 'fr',             // language (OPTIONAL)\n";
    $ret .= "                onlyMimes: [\"image\"]\n";
	$ret .= "			}).elfinder('instance');\n";
	$ret .= "		});\n";
	$ret .= "	</script>\n";*/
		$elfinderpath='elFinder-2.1.37';
	
		$ret .= '<script data-main="'.$elfinderpath.'/main.default.js" src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.5/require.min.js"></script>'."\n";
		$ret .= "<script>\n";
		$ret .= "	define('elFinderConfig', {\n";
		$ret .= '		defaultOpts : {'."\n";
		$ret .= "			url : '$elfinderpath/php/connector.minimal.php' // connector URL (REQUIRED)\n";
		$ret .= '			,commandsOptions : {'."\n";
		$ret .= '				edit : {'."\n";
		$ret .= '					extraOptions : {'."\n";
		$ret .= "						creativeCloudApiKey : '',\n";
		$ret .= "						managerUrl : ''\n";
		$ret .= "					}\n";
		$ret .= "				}\n";
		$ret .= "				,quicklook : {\n";
		$ret .= "					googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']\n";
		$ret .= "				}\n";
		$ret .= "			}\n";
		$ret .= '			,bootCallback : function(fm, extraObj) {'."\n";
		$ret .= "				fm.bind('init', function() {\n";
		$ret .= "				});\n";
		$ret .= "				var title = document.title;\n";
		$ret .= "				fm.bind('open', function() {\n";
		$ret .= "					var path = '',\n";
		$ret .= "						cwd  = fm.cwd();\n";
		$ret .= "					if (cwd) {\n";
		$ret .= '						path = fm.path(cwd.hash) || null;'."\n";
		$ret .= "					}\n";
		$ret .= "					document.title = path? path + ':' + title : title;\n";
		$ret .= "				}).bind('destroy', function() {\n";
		$ret .= "					document.title = title;\n";
		$ret .= "				});\n";
		$ret .= "			}\n";
		$ret .= "		},\n";
		$ret .= "		managers : {\n";
		$ret .= "			'elfinder': {}\n";
		$ret .= "		}\n";
		$ret .= "	});\n";
		$ret .= "</script>\n";		
        $ret .= "</head>\n";
    return $ret;
}

function admin_gallery_body() {
    $ret = "<body>\n";
    $ret .="    <div id=\"elfinder\"></div>\n";
    $ret .="    <div><a href=\"admin\">Retour</a></div>\n";
    $ret .= '</body>'."\n";
    return $ret;
}


/**
 * Fonction principale de l'administration
 */
function run_admin() {
    
    
    $adm=new ClAdmin();
    
    print $adm->display();
    
}

?>
