<?php
require_once("core/news.php");

class plugin {
    function plugin($balise,$content,$offset,$taille) {
        $this->offset=$offset;
        $this->taille=$taille;
        $this->name=$balise[0];
        $this->content=$content;
        $this->pageindex=0;
        
        $tab=array_slice($balise,1); 
        $this->parameters=array();
        foreach($tab as $val) {
            $n1=strpos($val,"=");
            if ($n1!==false) {
                $this->parameters[substr($val,0,$n1)] = substr($val,$n1+1);   
            }
        }
                 
    }
    
    function apply() {
        if ($this->name=="video") { 
            return $this->apply_video();

        } else if ($this->name=="lemouv") { 
            return $this->apply_lemouv();

        } else if ($this->name=="facebook") { 
            return $this->apply_facebook();

        } else if ($this->name=="facebook_like") { 
            return $this->apply_facebook_like();

        } else if ($this->name=="paypal") { 
            return $this->apply_paypal();

            } else {
            return false;   
        }          
    }

    private function get_param($param,$default) {
        if (array_key_exists($param,$this->parameters)){
            return $this->parameters[$param];    
        } else {
            return $default;
        }
            
    }    
    
    function apply_paypal() {
        $paypal  = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">'."\n";
        $paypal  .= '  <input type="hidden" name="cmd" value="_s-xclick" />'."\n";
        $paypal  .= '  <input type="hidden" name="hosted_button_id" value="APZTQ7VJR3TQW" />'."\n";
        $paypal  .= '  <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus s&eacute;curis&eacute;e !" />'."\n";
        $paypal  .= '  <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />'."\n";
        $paypal  .= "</form>\n";
       
        return $paypal;        
    }
    
    function apply_video() {
        $width=$this->get_param("width","531");
        $height=$this->get_param("height","298");;
        return '<iframe src="'.$this->content.'" frameborder="0" width="'.$width.'" height="'.$height.'"></iframe>';
        
    }

    function apply_lemouv() {
        return '<iframe src="http://www.lemouv.fr/player/export-reecouter?content='.$this->content.' "width="481" frameborder="0" scrolling="no" height="139"></iframe>';
    }
    
    function apply_facebook() {
        $ret = "";
        /*$ret  = '        <div id="fb-root"></div>'."\n";
        $ret .= '        <script type="text/javascript">(function(d, s, id) {';
        $ret .= 'var js, fjs = d.getElementsByTagName(s)[0];';
        $ret .= 'if (d.getElementById(id)) return;';
        $ret .= 'js = d.createElement(s); js.id = id;';
        $ret .= 'js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";';
        $ret .= 'fjs.parentNode.insertBefore(js, fjs);';
        $ret .="'}(document, 'script', 'facebook-jssdk'));</script>\n";*/
        
        $ret .='        <div class="fb-follow" data-href="https://www.facebook.com/pages/Association-Tous-Semblables-Tous-Diff%C3%A9rents/284950108189664?fref=ts" data-width="450" data-show-faces="true"></div>'."\n";
        $ret .='        <div class="news">'."\n";
        $ret .= get_news();
        $ret .="        </div>\n";
        return $ret;
    }
    
    function apply_facebook_like() {
        $ret = "";
      /*  $ret = '        <div id="fb-root"></div>'."\n";
        $ret .= '        <script type="text/javascript">(function(d, s, id) {'."\n";
        $ret .= '          var js, fjs = d.getElementsByTagName(s)[0];'."\n";
        $ret .= '          if (d.getElementById(id)) return;'."\n";
        $ret .= '          js = d.createElement(s); js.id = id;'."\n";
        $ret .= '          js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";'."\n";
        $ret .= '          fjs.parentNode.insertBefore(js, fjs);'."\n";
        $ret .= "        }(document, 'script', 'facebook-jssdk'));</script>\n";*/
        
        $page="http://www.assotstd.com";
        if ($this->pageindex!=0) {
            $page.="/index?page=".strval($this->pageindex);
        }
        $ret .= '<div class="fb-like" data-href="'.$page.'" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>'."\n";
        return $ret;
    }
    
}



$plugin_list=array ( "video","lemouv","facebook", "paypal","facebook_like" );


function get_balise($content) {
    
    $n1=strpos($content,"{");
    $n2=strpos($content,"}",1);
    global $plugin_list;
      
    if (($n1!==false) && ($n2!==false)) {
        $balise=substr($content,$n1+1,$n2-$n1-1);
          
        $balise=explode(' ',$balise);
        
        //search end of balise
        $n3=strpos($content,"{/$balise[0]}",$n2+1);
        
        //get balise
        $obj=null;
        foreach ($plugin_list as $name) {
            if ($balise[0]==$name) {
                $value=substr($content,$n2+1,$n3-$n2-1);
                $obj=new plugin($balise,$value,$n1,$n3+3+strlen($balise[0])-$n1);
            }
        }
        
        if (is_null($obj)) { return false;}

        return $obj;

      } else {
          return false;
      }    
 
}



function apply_plugins(&$content,$pageindex) {
    $obj = true;
    
    while ($obj!==false) {
        $obj = get_balise($content);
        if ($obj===false) {break;}
        $obj->pageindex=$pageindex;
        $res=$obj->apply();
        if ($res!==false) {
            $newcontent=substr($content,0,$obj->offset);
            $newcontent.=$obj->apply();
            $newcontent.=substr($content,$obj->offset+$obj->taille);
            $content=$newcontent;
        }
    }        
}


?>
