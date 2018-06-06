<?php
require_once("core/base.php");
require_once("core/mainmenu.php");
require_once("core/news.php");
require_once("core/plugin.php");
require_once("core/article.php");
require_once("core/gallery.php");
require_once("core/contact.php");

function page($head,$body) {
    
   $ret = '<!DOCTYPE html>'."\n";
   $ret .= '<html lang="fr">'."\n";
   $ret .= $head;
   $ret .= $body;
   $ret .= '</html>'; 
   return $ret;   
    
}

function entete() {
    
    $ret = "<head>\n";
    $ret .= '<title>Tous semblables tous différents</title>'."\n";
    $ret .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n";
    $ret .= "<!--[if lt IE 9]>\n";
    $ret .= "    <script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script>\n";
    $ret .= "<![endif]-->";
    //$ret .= '<meta name="viewport" content="width=device-width; initial-scale=1.0;">'."\n";
    $ret .= '<meta name="viewport" content="width=device-width" />'."\n";
    $ret .= '<link href="css/main.css" rel="stylesheet" media="all" type="text/css" />'."\n";
    $ret .= '<link href="css/menu.css" rel="stylesheet" media="all" type="text/css" />'."\n";
    $ret .= '<link rel="stylesheet" media="handheld, only screen and (max-device-width: 480px)" type="text/css" href="css/mobile.css" />'."\n";
    $ret .= '<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />'."\n";
    $ret .= '<link href="http://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet" type="text/css">'."\n";
    $ret .= '<link href="http://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet" type="text/css">'."\n";
    $ret .= '<link href="http://fonts.googleapis.com/css?family=Coming+Soon" rel="stylesheet" type="text/css">'."\n";
    $ret .= '<link href="http://fonts.googleapis.com/css?family=Amatic+SC" rel="stylesheet" type="text/css">'."\n";

    $ret .= '<link href="diaporama/style.css" rel="stylesheet" media="all" type="text/css" />'."\n";
    
  /*  $ret .= '<script type="text/javascript" src="diaporama/jquery-1.3.2.min.js"></script>'."\n";
    $ret .= '<script type="text/javascript" src="diaporama/jquery.diaporama.js"></script>'."\n";
    $ret .= '<script type="text/javascript" src="diaporama/script.js"></script>'."\n";*/

    //Add jQuery library
    $ret .= '<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>'."\n";
    //Add mousewheel plugin (this is optional)
    $ret .= '<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>'."\n";
    //Add fancyBox
    $ret .= '<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />'."\n";
    $ret .= '<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>'."\n";

    //Add camera (diapo)
    $ret .= '<link href="camera/css/camera.css" rel="stylesheet" media="all" type="text/css" />'."\n";
    $ret .= '<script type="text/javascript" src="camera/scripts/jquery.min.js"></script>'."\n";
    $ret .= '<script type="text/javascript" src="camera/scripts/jquery.easing.1.3.js"></script>'."\n";
    $ret .= '<script type="text/javascript" src="camera/scripts/jquery.mobile.customized.min.js"></script>'."\n";
    $ret .= '<script type="text/javascript" src="camera/scripts/camera.min.js"></script>'."\n";

    //Optionally add helpers - button, thumbnail and/or media
    $ret .= '<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />'."\n";
    $ret .= '<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>'."\n";
    $ret .= '<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>'."\n";
    $ret .= '<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />'."\n";
    $ret .= '<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>'."\n";

    $ret .= '<script type="text/javascript">'."\n";
    $ret .= "window.onresize = resize;";
    $ret .= "function resize() {";
    $ret .= "var largeur=document.body.clientWidth;";
    $ret .= "if (largeur<1200) {";
    $ret .= "document.getElementById('aside_droite').style.visibility='hidden';";
    $ret .= "} else {";
    $ret .= "document.getElementById('aside_droite').style.visibility='visible';";
    $ret .= "}}";
    $ret .= "</script>\n";


    $ret .= '</head>'."\n";
    return $ret;
}

function mainmenu() {
    global $mainmenus;
    
    $ret = "";
    foreach ($mainmenus as $menu) {
        $ret .= $menu->display();
    }
    return $ret;
}    

function body_content($contenu) {
    $ret = '        <div class="moncontent">'."\n";
    $ret .= $contenu;
    $ret .= '        </div>';
    return $ret;
}

function body_milieu($contenu,$classtitre) {
    $ret  = '      <div class="milieu">'."\n";
    //$ret .= '      <div class=\"titre\" ><img src="images/'.$titre.'" width="622" height="86" /></div>'."\n";
    $ret .= "        <div class=\"$classtitre\" ><p>Association<br/>TOUS SEMBLABLES TOUS DIFFERENTS</p></div>\n";
    $ret .= body_content($contenu);
    $ret .= '      </div>';
    return $ret;  
}

function body($backgroundimage,$titre,$menu,$contenu,$droite,$pageindex) {
    $ret  = "  <body style=\"background-image:url(images/$backgroundimage)\">\n";
    $ret .= "    <div id=\"page\">\n";
	
	//Facebook
    $ret .= '      <div id="fb-root"></div>'."\n";
    $ret .= '      <script type="text/javascript">(function(d, s, id) {'."\n";
    $ret .= '          var js, fjs = d.getElementsByTagName(s)[0];'."\n";
    $ret .= '          if (d.getElementById(id)) return;'."\n";
    $ret .= '          js = d.createElement(s); js.id = id;'."\n";
    $ret .= '          js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";'."\n";
    $ret .= '          fjs.parentNode.insertBefore(js, fjs);'."\n";
    $ret .= "        }(document, 'script', 'facebook-jssdk'));\n";
    $ret .= "      </script>\n";

	//Header
    $ret .= "      <header id=\"main\">\n";
    $ret .='         <logo><a href="index"><img name="logo" src="images/logo.gif" width="190" height="272" alt="" /></a></logo>'."\n";
	$ret .="         <a href=\"index\"><img src=\"images/entete.png\" /></a>\n";
    $ret .= "      </header>\n";
    
    //Navigation
    $ret .= "      <section id=\"corp\">\n";
    $ret .= "        <nav>\n";	
	$page="http://www.assotstd.com";
    if ($pageindex!=0) {
            $page.="/index?page=".strval($pageindex);
    }
    $ret .= '         <div style="height:30px;" class="fb-like" data-href="'.$page.'" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>'."\n";
    $ret .= $menu;  
    $ret .= "        </nav>\n";
    
    //Contenu
    $ret .= "        <section id=\"contenu\">\n";
    $ret .= $contenu;
    $ret .= "            <aside id=\"aside_droite\">\n";
    $ret .= $droite;
    $ret .= "            </aside>\n";
    //Petit script pour supprimer le aside si la fenetre est trop petite
    $ret .= "<script type=\"text/javascript\">resize();</script>\n";
    $ret .= "        </section>\n";       
    $ret .= "        <footer>\n";
    $ret .= "            <p>Tous semblables tous différents est une association à but non lucratif<br />\n";
    $ret .= "            <a href=\"http://www.assotstd.com/index?page=5\">Me contacter !</a></p>\n";
    $ret .= "        </footer>\n";
	$ret .= "      </section>\n";
    $ret .= "    </div>\n";    
    $ret .= "  </body>\n";
    return $ret;
}



/**
 * Recherche le menu parent
 */
function parent_menu($component) {
    global $mainmenus;
    global $menus;
    global $gallery_lists;

    if ($component->pid==-1) {
        return null;
    }

    //recherche le menu associe
    foreach ($menus as $menu) {
        if ($menu->id==$component->pid) {
            return $menu;
        }
    }
    
    //recherche le main menu associe
    foreach ($mainmenus as $menu) {
        if ($menu->id==$component->pid) {
            return $menu;
        }
    }   

    //recherche le galerie menu associe
    foreach ($gallery_lists as $menu) {
        if ($menu->id==$component->pid) {
            return $menu;
        }
    }   
    
}

/**
 * Recherche le contenu d'un menu
 */
function menu_content($id) {
    global $articles;
    global $contacts;
    global $galeries;
    global $gallery_lists;
    
    foreach ($articles as $art) {
        if ($art->pid==$id) {
           return $art;
        }
    }
    
    foreach ($contacts as $contact) {
        if ($contact->pid==$id) {
           return $contact;
        }
    }           

    foreach ($gallery_lists as $galerie) {
        if ($galerie->pid==$id) {
           return $galerie;
        }
    }           

    foreach ($galeries as $galerie) {
        if ($galerie->pid==$id) {
           return $galerie;
        }
    }           
    
    return null;
    
}

/**
 * Retourne le menus au format HTML
 */
function get_menus($mainmenu) {
        global $menus;
        global $mainmenus;
        global $galeries;
        global $gallery_lists;
        
        $menulst = "<ul>\n";
        foreach ($mainmenus as $mm) {
            $menulst .= "<li class=\"menul1\">";
            $menulst.='<a href="index?page='.$mm->id.'"> '.$mm->longname.='</a>';  
            $menulst.="</li>\n";
            if ($mainmenu->id==$mm->id) {               
               $cpt=1;
               //Sous menus
               foreach($menus as $m) {                 
                    if ($m->pid==$mainmenu->id) {
                        if ($cpt==1){
                            $menulst.="<ul>";
                        }
                        $menulst.='<li class="menul2">';
						if ($m->link=='') {
						    $menulst.='<a href="index?page='.$m->id.'"> '.$m->longname.='</a>';
                        } else {
						    $menulst.='<a href="'.$m->link.'"> '.$m->longname.='</a>';
						}
                        $menulst.='</li>';
						$cpt=0;
                    }
                }                    
               //Galeries
               //Les galeries sont definies dans $galeries et sont rattaché à une liste ($gallery_list)
               foreach($galeries as $gal) {                 
                    //on cherche le PID de la liste de galeries
                    foreach($gallery_lists as $gallist){
                        if (($gallist->id == $gal->pid)&& ($gallist->pid==$mainmenu->id)) {
                            if ($cpt==1){
                                $menulst.="<ul>";
                            }
                            $menulst.='<li class="menul2"><a href="index?page='.$gal->id.'"> '.$gal->name.='</a></li>';
                            $cpt=0;
                        }
                    }
                }                    
               if ($cpt!=1){$menulst.="</ul>";}
            }
        }
        $menulst .="</ul>\n";
        return $menulst;        
}

function projet_encours() {
        $ret     = "<a href=\"index?page=32\">";
        $ret    .= "<p class=\"projetencours\">Trek for smiles<br/>";
        $ret    .= "<img name=\"\" src=\"images/affiches/TrekForSmiles.jpeg\" alt=\"\" />";
        $ret    .= "</p>";
        $ret    .= "</a>";
		$ret    .= "\n";

		$paypal  = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
		$paypal  .= '<p class="dons">';
        $paypal  .= '<input type="hidden" name="cmd" value="_s-xclick" />';
        $paypal  .= '<input type="hidden" name="hosted_button_id" value="APZTQ7VJR3TQW" />';
        $paypal  .= '<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !" />';
        $paypal  .= '<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />';
        $paypal  .= "</p></form>";
        
        $ret    .= "<p><strong>Aidez l'association en faisant un don</strong></p>".$paypal;

		
		
		$ret    .= "<div class=\"sociaux\"><table>";
		$ret    .= "<tr>";

        $ret    .= "<td><a href=\"https://www.instagram.com/martinm_photographer\" target=\"blank\" >";
		$ret    .= "Instagram<br/>";
        $ret    .= "<img src=\"images/instagram.jpg\"/>";
        $ret    .= "</a></td>\n";
		
        $ret    .= "<td><a href=\"https://www.facebook.com/assotstd\" target=\"blank\" >";
		$ret    .= "Facebook<br/>";
        $ret    .= "<img src=\"images/FB-fLogo-Blue-broadcast-2.png\"/>";
        $ret    .= "</a></td>\n";
        $ret    .= "</tr><tr>\n";
		
		$ret    .= "<td><a href=\"https://www.youtube.com/user/zeglobetrotter\" target=\"blank\" >";
		$ret    .= "Youtube<br/>";
        $ret    .= "<img src=\"images/logoyoutube.jpg\"/>";
        $ret    .= "</a></td>\n";

		$ret    .= "<td><a href=\"https://www.linkedin.com/company/association-tstd\" target=\"blank\" >";
		$ret    .= "Linkedin<br/>";
        $ret    .= "<img src=\"images/In-2C-59px-TM.png\"/>";
        $ret    .= "</a></td>\n";

		

		
		$ret    .= "</tr>";
		$ret    .= "\n";

		$ret    .= "</table></div>";

		$ret    .= "\n";
        
        return $ret;
}

/**
 * Execute l'action du menu 
 */
function run_tstd($identifiant) {
    global $mainmenus;
    global $menus;
    global $articles;
    global $contacts;
    global $galeries;
    global $gallery_lists;

    $components=array_merge($mainmenus,$menus,$articles,$contacts,$galeries,$gallery_lists);
    $titre="";
    $background="";
    $droite="";
    $submenu="";
    $contenu="Page inconnue";
    
    $entete=entete();
    
    //recherche l'element de l'identifiant
    $cmpnt=null;
    if ($identifiant>0) {
        foreach ($components as $cmpnt) {
            if ($cmpnt->id == $identifiant) {
                $component=$cmpnt;
            }
        }
    }
    
    if ($component==null) {
        //page home
        $background= 'index_r6_c6.gif';
        $titre     = 'titre_1';
        /*$submenu   = '<img name="index_r4_c1" src="images/index_r4_c1.gif" width="190" height="361" id="index_r4_c1" alt="" />';
        $submenu  .= "\n";*/
        $submenu = "<ul>";
        foreach ($mainmenus as $menu) {
            $submenu .= "<li>";
            $submenu.='<a href="index?page='.$menu->id.'"> '.$menu->name.='</a>';  
            $submenu.="</li>\n";
        }
        $submenu .="</ul>\n";        
       
        $droite    = projet_encours();
        
        $contenu  = "           <article>\n";
	    $contenu .= get_article("index",0);
        $contenu .= "           </article>\n";
        
        $body=body($background,$titre,$submenu,$contenu,$droite,0);
           
    } else {
        
        $mainmenu=null;
        $submenu=null;
        $article=null;
        
        if ($component->pid==-1) {
             //On a selectionne un menu principal
             $mainmenu=$component;
             
            //$article=build_menu_list($mainmenu->id);
             
            //display the first submenu
            foreach ($menus as $menu) {
                if ($menu->pid==$mainmenu->id) {
                    $submenu=$menu;
                    break;
                }  
            }
            
        } else {
           
            //recherche le menu parent
            $parentmenu=parent_menu($component);
            //recherche le main menu
            $mainmenu=parent_menu($parentmenu);
            while(($mainmenu!=null) && ($mainmenu->pid!=-1)){
                $mainmenu=parent_menu($mainmenu);
            }

            if ($mainmenu==null) {
                //on a selectionne un menu
                $mainmenu=$parentmenu;
                $submenu=$component;
            } else {
                //on a selection un article
                $submenu=$parentmenu;
                $article=$component;
            }
            
            $contenu=$component->display();

            
        }
        if ($article==null) {
            if ($submenu!=null) {
                //on a selectione un menu => recherche l'article associe
                $article=menu_content($submenu->id);
                
                
            } else if ($mainmenu!=null) {
               //on a selectione un menu principal et il n y a pas de sous menu, recherche l'article associe
               $article=menu_content($mainmenu->id);
            }
        }
        
        if ($article!=null) {
            $contenu  = "           <article>\n";
            $contenu .= "             <header class=\"titre_article\" >";
            if ($submenu->longname!="") {
                $contenu .= $submenu->longname;
            } else {
                $contenu .= $article->name;
            }
            $contenu .="</header>\n";
            $contenu .= "             <div class=\"contenu_article\">\n".$article->display()."\n";
            $contenu .= "             </div>\n";
            $contenu .= "           </article>\n";
            $droite=projet_encours();
        }
        
        if ($mainmenu!=null) {
            $background=$mainmenu->background;
            $titre=$mainmenu->titre;
        } else {
            //pas de main menu, peut etre que c'est le parent
              
        }
        
        //Menus
        $menulst = get_menus($mainmenu);

        $body=body($background,$titre,$menulst,$contenu,$droite,$component->id);
    }
    
    print page($entete,$body);

}


?>
