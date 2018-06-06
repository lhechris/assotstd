<?php

require_once("core/base.php");

/** 
 * Main menu
 */
class mainmenu extends component{
    /**
     * id       : identifiant unique
     * name     : le nom du menu
     * img      : l'image du menu
     * imghover : l'image en surbrillance
     * imgclick : l'image quand on clic
     * width    : largeur de l'image
     * height   : longueur de l'image
     * background : l'image de fond
     * titre      : l'image du titre
     */
    function mainmenu($id,$shortname,$name,$img,$imghover,$imgclick,$width,$height,$background,$titre) {
        $this->imgclick=$imgclick;
        $this->img=$img;
        $this->imghover=$imghover;
        $this->background=$background;
        $this->titre=$titre;
        $this->width=$width;
        $this->height=$height;
        $this->longname=$name;
        parent::__construct($id,-1,$shortname);
    } 
  
    function display() {
        $ret="<li>";
        $ret.='<a href="index?page='.$this->id.'"> ';
		$ret.=$this->name;
        //$ret.='<img name="'.$this->name.'" src="images/'.$this->img.'" width="'.$this->width.'" height="'.$this->height.'" id="'.$this->name.'" alt="'.$this->longname.'" />';
        $ret.='</a>';
        $ret.='';        
        $ret.="</li>";
        return $ret;
    }
}

/**
 * Menu 
 */
class menu extends component {
    
    function menu($id,$pid,$shortname,$img,$imghover,$name,$link) {
        $this->img=$img;
        $this->imghover=$imghover;
        $this->link=$link;
        $this->submenu=array();
        $this->thumbnail="/images/miniature/".$shortname.".png";
        $this->longname=$name;        
        parent::__construct($id,$pid,$shortname);
    }
    
    function display() {
        $ret="<li class=\"submenu\">";
        if ($this->link=='') {
            $ret.='<a href="index?page='.$this->id.'" >';
        } else {
            $ret.='<a href="'.$this->link.'" >';
        }

      /*  $ret.='<img name="'.$this->name.'" src="images/'.$this->img.'" alt="'.$this->longname.'" ';
        if ($this->imghover!='') {
            $ret.='onmouseover="'.$this->name.".src='images/".$this->imghover."'".'" onmouseout="'.$this->name.".src='images/".$this->img."'".'"';
        }
        $ret.='/>';*/
        $ret .= "<span>$this->longname</span>\n";
        //$ret.="<span style=\"font-family: BrushScriptMT1, Helvetica, Arial, sans-serif;\">".$this->longname."</span>\n";          

        $ret.='</a>';
        
        $ret.="<hr/></li>\n"; 
        return $ret;       
    }
}


class menulist extends component {
    
    private $tabmenus;
    
    function menulist($pid,$tabmenus) {
        parent::__construct(-1,$pid,"");
        $this->tabmenus=$tabmenus;
        $this->display_menulist=false;
    }
    
    function display() {
        $ident="          ";
        $ret = $ident."<table>\n";
        $cpt=0;
        foreach($this->tabmenus as $menu) {
            if ($cpt==0) {
                $ret.=$ident."  <tr>\n";
            } else if ($cpt%4==0) {
                $ret.=$ident."  </tr>\n".$ident."  <tr>\n";
            }
            $ret.=$ident."    <td width=\"200\">";
            if ($menu->link=='') {
                $ret.='<a href="index?page='.$menu->id.'" >';
            } else {
                $ret.='<a href="'.$menu->link.'" >';
            }
            
            $path1=substr($menu->thumbnail,1);
            $path2="images/miniature/notfound.png";
             
            $img=file_exists($path1)? $path1 : $path2;
            
            $ret.='<img name="" src="/'.$img.'" alt="'.$menu->longname.'" >';
            
            $ret.="";
            $ret.="<p>".$menu->longname."</p>";
            $ret.="</a></td>\n";
            $cpt++;
        }
        if ($cpt!=0) {
            $ret.=$ident."  </tr>\n";
        }
        $ret.=$ident."  </table>\n";
        
        return $ret;       
    }
}


$mainmenus=array(
    new mainmenu(2,"association","L'association","association.gif","association_s2.gif",'association_s3.gif',177,16,'association_r1_c14.gif',"titre_3"),
    new mainmenu(3,"projets",    "Les projets","project.gif",    "project_s2.gif",    'project_s3.gif',    113,16,'association_r1_c14.gif'      ,'titre_4'),
    new mainmenu(1,"actions",    "Actions",  "actions.gif",    "actions_s2.gif",    'actions_s3.gif',    113,16,'fond_bleu.png',    'titre_2'),
    new mainmenu(4,"galerie",    "Les galeries photos","gallery.gif",    "gallery_s2.gif",    'gallery_s3.gif',    114,16,'fond_rose.png' ,'titre_5'),
    new mainmenu(30,"vidéos",    "Les Vidéos","",    "",    '',    112,16,'index_r6_c6.gif','titre_5'),
    new mainmenu(5,"contact",    "Contact","contact.gif",    "contact_s2.gif",    'contact_s3.gif',    112,16,'index_r6_c6.gif','titre_1')
    );

$menus=array(
    new menu(6,2 ,'association','menu_histo.png',   'menu_histo_over.png',   "L'association",''),
    new menu(7,2 ,'team',       'menu_team.png',    'menu_team_over.png',    "L'&eacute;quipe",''),
    new menu(8,2 ,'objectif',   'menu_objectif.png','menu_objectif_over.png',"Objectif",''),
    new menu(9,2,'helpus',      'menu_helpus.png',  'menu_helpus_over.png',  "Aidez nous",''),
    new menu(10,2,'concours',   'menu_concours.png','menu_concours_over.png',"Concours",''),  
    
    new menu(11,1,'prj_sene',       'menu_prj_sene.png',       'menu_prj_sene_over.png',       "Projet S&eacute;n&eacute;gal 2010",""),
    new menu(12,1,'sej_hum_sene',   'menu_sej_hum_sene.png',   'menu_sej_hum_sene_over.png',   "S&eacute;jour humanitaire S&eacute;n&eacute;gal",''),
    new menu(13,1,'rep_adedvi',     'menu_rep_adedvi.png',     'menu_rep_adedvi_over.png',     "Reportage ADEDVI",''),
    new menu(14,1,'rep_tdm',        'menu_rep_tdm_2012.png',   'menu_rep_tdm_2012_over.png',   "Reportage Tour du monde",''),
    new menu(15,1,'solidarity_show','menu_solidarity_show.png','menu_solidarity_show_over.png',"Solidarity Show",''),
    new menu(16,1,'gospel',         'menu_gospel.png',         'menu_gospel_over.png',         "Concert de Gospel",''),
    new menu(17,1,'poker',          'menu_poker.png',          'menu_poker_over.png',          "Tournois de Poker",''),
    new menu(18,1,'spectacle',      'menu_spectacle.png',      'menu_spectacle_over.png',      "Spectacle",''),
    new menu(19,1,'village_assoc',  'menu_village.png',        'menu_village_over.png',        "Village des associations",''),
    new menu(20,1,'blog',           'menu_blog.png',           'menu_blog_over.png',           "Blog de l'association",'http://assotstd.over-blog.com'),
    new menu(21,1,'blogtdm',        'menu_blog_tdm.png',       'menu_blog_tdm_over.png',       "Blog tour du monde",'http://zeglobetrotter.blogspot.fr'),
    
    new menu(32,3,'trek_for_smiles', "menu_cameroun.png",  "menu_cameroun_over.png","Projet Trek for smiles",''),
    new menu(31,3,'benin',          "menu_cameroun.png",  "menu_cameroun_over.png","Projet Benin",''),
    new menu(27,3,'souriredumonde', '',       '',       "Sourire du monde",''),
    new menu(26,3,'partageunmonde', '',       '',       "Partage un monde",''),
    new menu(24,3,'inde',           "menu_inde.png",      "menu_inde_over.png","Projet Inde",''),
    new menu(23,3,'tdm',            "menu_tdm.png",       "menu_tdm_over.png","Tour du monde humanitaire ",''),
    new menu(25,3,'cameroun',       "menu_cameroun.png",  "menu_cameroun_over.png","Projet Cameroun",''),
    new menu(22,3,'senegal',        "menu_senegal.png",   "menu_senegal_over.png","Ado au S&eacute;n&eacute;gal",''),
    new menu(28,3,'medias',         "menu_medias.png",    "menu_medias_over.png","M&eacute;dia",''),
    new menu(29,3,'sponsor',        "menu_sponsor.png",   "menu_sponsor_over.png","Ils nous ont soutenus",'')
    );

/**
 * Retourne la liste des sous menus associe au mainmenu
 */
function get_menus_list($id) {
    global $menus;
    
    $tab=array();
    
    foreach ($menus as $m) {
        if ($m->pid==$id) {
            array_push($tab,$m);
        }
    }
    if (count($tab)==0) {
       return null;
    } else {
        return $tab;
    }
}    



/**
 * cree un component de liste des articles des menus
 * $id l'id du mainmenu
 */
function build_menu_list($id) {
    $tabmenus=get_menus_list($id);

    if ($tabmenus==null) {
        return null;
    } else {
        return new menulist($id,$tabmenus);
    }
    
    
}
    
?>
