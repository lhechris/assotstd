<?php

require_once("core/base.php");


class gallery extends component {

    function gallery($id,$pid,$name,$directory,$ispaid) {
        $this->directory = "elFinder-2.1.37/files/$directory";
        $this->miniature=$this->getfirstminiature();
        $this->ispaid=$ispaid;
        parent::__construct($id,$pid,$name);
    }

    function getfirstminiature() {
        $ret=$this->directory."/miniatures";
        $finfo = finfo_open(FILEINFO_MIME_TYPE); 
        foreach (glob($this->directory."/*.*") as $filename) {
            $mimetype=finfo_file($finfo, $filename);
            if (substr($mimetype,0,5)=="image") {
                $ret.="/".basename($filename);
                break;
            }
        }
        finfo_close($finfo);
        return $ret;
    
    }
    
    function getlistimg() {
        $list=array();
        $finfo = finfo_open(FILEINFO_MIME_TYPE); 
        foreach (glob($this->directory."/*.*") as $filename) {
            $mimetype=finfo_file($finfo, $filename);
            if (substr($mimetype,0,5)=="image") {
                array_push($list,$filename);
            }
        }
        finfo_close($finfo);
        return $list;
    }
 
    //Cree l'image miniature du fichier
    //si elle n'existe pas
    function getminiature($filename) {
        
        $pinfo=pathinfo($filename);
        $miniaturedir=$pinfo['dirname'].'/miniatures';
        $miniature=$miniaturedir.'/'.$pinfo['basename'];
        
        if (!file_exists($miniature)) {
            //tentative avec imagick mais non pris en charge par OVH
        /*    $image = new Imagick($filename);
            $image->thumbnailImage(100, 0);
            $hdl=fopen($miniature);
            fwrite($hdl,$image);*/
        
            //cree le repertoire miniature dans le repertoire
            if (!file_exists($miniaturedir)){
                mkdir($miniaturedir);
            }
        
            $tableau = @getimagesize($filename);
            $ratio = 150;
            if ($tableau[2] == 2) {
                //JPEG 
                $src = imagecreatefromjpeg($filename);
                // on teste si notre image est de type paysage ou portrait
                if ($tableau[0] > $tableau[1]) {
                    $im = imagecreatetruecolor(round(($ratio/$tableau[1])*$tableau[0]), $ratio);
                    imagecopyresampled($im, $src, 0, 0, 0, 0, round(($ratio/$tableau[1])*$tableau[0]), $ratio, $tableau[0], $tableau[1]);
                }
                else {
                    $im = imagecreatetruecolor($ratio, round(($ratio/$tableau[0])*$tableau[1]));
                    imagecopyresampled($im, $src, 0, 0, 0, 0, $ratio, round($tableau[1]*($ratio/$tableau[0])), $tableau[0], $tableau[1]);
                }
                // on copie notre fichier généré dans le répertoire des miniatures
                imagejpeg ($im, $miniature);        
            }
          	elseif ($tableau[2] == 3) {
                //PNG
                $src = imagecreatefrompng($filename);
                if ($tableau[0] > $tableau[1]) {
                    $im = imagecreatetruecolor(round(($ratio/$tableau[1])*$tableau[0]), $ratio);
                    imagecopyresampled($im, $src, 0, 0, 0, 0, round(($ratio/$tableau[1])*$tableau[0]), $ratio, $tableau[0], $tableau[1]);
                }
                else {
                    $im = imagecreatetruecolor($ratio, round(($ratio/$tableau[0])*$tableau[1]));
                    imagecopyresampled($im, $src, 0, 0, 0, 0, $ratio, round($tableau[1]*($ratio/$tableau[0])), $tableau[0], $tableau[1]);
                }
                imagepng ($im, $miniature);
            }
            else {
                //l'image n'est ni jpg ni png affiche unknown
                $miniature='images/miniature/notfound.png';
            }
        }
        return $miniature;
    }
 
    function diaporama() {
        $list=$this->getlistimg();
        
        /*$diaporama='<div class="diaporama"> '."\n";
        $diaporama.="  <ul>\n";
        foreach ($list as $filename) {
            
            $diaporama.='    <li><img src="'.$filename.'" alt="" width="800" height="600" /></li>'."\n";  
        }
        $diaporama.='  </ul>'."\n";  
        $diaporama.='</div>'."\n";  */

        foreach ($list as $filename) {
            $diaporama.="<div data-src=\"".$filename."\" data-thumb=\"".$this->getminiature($filename)."\" data-portrait=\"true\" data-link=\"$filename\" data-target=\"_blank\">";
            //$diaporama.="<div class=\"camera_caption fadeFromBottom\">".$filename."</div>";

            $diaporama.="</div>";  
        }
        return $diaporama;    
   
    }

    function grille() {
        $list=$this->getlistimg();
        $ret="";
        $ret.='<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">'."\n";
        $ret.="<script>";
        $ret.='$(document).ready(function() {'."\n";
        $ret.='    $(".fancybox").fancybox({'."\n";
        $ret.="        openEffect	: 'elastic',\n";
        $ret.="        closeEffect	: 'elastic',\n";
        $ret.="        helpers : {\n";
        $ret.="            title : {\n";
        $ret.="                type : 'inside'\n";
        $ret.="            }\n";
        $ret.="        }\n";
        $ret.="    });\n";
        $ret.="});\n";
        $ret.="</script>\n";
        $ret.='<div class="gal_grid"> '."\n";
        foreach ($list as $filename) {
            $nomphoto=pathinfo($filename)['filename'];
            $ret.='    <div>';
            $ret .= '<table><tr><td>';
            $ret .= $nomphoto;
            $ret .= '</td></tr><tr><td>';
            $ret.='<a class="fancybox" rel="group" href="'.$filename.'"><img src="'.$this->getminiature($filename).'" alt="" /></a>';
            $ret .= '</td></tr><tr><td>';
            if ($this->ispaid) {
                $ret .= '<a href="index?buy='.pathinfo($filename)['basename'].'">Acheter</a></div>';
            }
            $ret .= '</td></tr></table>';
            $ret.='</div>'."\n";
        }
        $ret.='</div>'."\n";    
        $ret.="</form>\n";
        return $ret;    
   
    }

    function display() {

        //$ret = $this->diaporama();
        $ret = $this->grille();
        return $ret;

    }
    
    function droite() {
        $ret = "";
        if ($this->ispaid) {
            $ret = '<img name="affiche" src="images/affiches/affiche expo-vente-mini.jpg" width="150" height="254" id="index_r1_c17" alt="" />';
        }
        return $ret;
    }
        
}

function build_galeries() {

    $dir='elFinder-2.1.37/files';
    $currentid=301;
    
    $galeries=array();
    
    $cdir = scandir($dir);
    foreach ($cdir as $value) 
    if (!in_array($value,array(".","..")))
    {
        if ((is_dir($dir . DIRECTORY_SEPARATOR . $value)) && ($value[0]!='.'))
        {
            //$result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
            $ispaid=false;
            /*if ($value=="Exposition Espace Renoir") {
                $ispaid=true;
            }*/
            array_push($galeries,new gallery($currentid,300,$value,$value,$ispaid));
            $currentid++;
        }
   }  
   return $galeries;  
}

//$galeries=array( new gallery(48,47,'gallery','1','gal_1_min.jpg'));
$galeries=build_galeries() ;


class gallerylist extends component {


    function display() {
        global $galeries;
        
        $js .= "<script>\n";
		$js .= "	jQuery(function(){\n";
		$js .= "\n";
       
        $cpt=1;
        $ret="";
        foreach($galeries as $gal) {
            $ret.="<article class=\"galerie\">\n";
            $ret.="  <header>$gal->name</header>\n";
            /*$ret.="  <a href=\"index?page=$gal->id\" style=\"text-decoration:none;\">\n";
            $ret.="    <div class=\"gal_lists_cadre\">\n";
            $ret.="      <img src=\"$gal->miniature\" width=\"160\" height=\"105\"/>\n";
            $ret.="    </div>\n";
            $ret.="    <p style=\"font-family:'Comic Sans MS'; text-align:center;\">$gal->name</p>\n";
            $ret.="  </a>\n";*/

            $js .= "		jQuery('#camera_wrap_$cpt').camera({\n";
            $js .= "			thumbnails: true,\n";
            $js .= "          fx:'simpleFade',\n";
            $js .= "          loader:'none',\n";
            $js .= "		});\n";


            $ret.="  <div class=\"camera_wrap camera_azure_skin\" id=\"camera_wrap_$cpt\">\n";            
            $ret.=$gal->diaporama();
            $ret.="\n  </div>\n";
            $ret.="</article>\n";
            
            $cpt+=1;
        }
        
 		$js .= "\n";
		$js .= "	});\n";
		$js .= "</script>\n"; 
        
        return $js."\n".$ret;
       
    }

}

$gallery_lists=array(new gallerylist(300,4,'Galeries'));

?>
