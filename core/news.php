<?php
//require_once("rsslib/rsslib.php");
//require_once ('tmp/PicoFeed/Reader.php');

function get_rss_string($flux) {
    $ret="";
    $donnee = $flux->channel;    
    foreach($donnee->item as $valeur){
        $ret.= '<p class="contentnews">'.date("d/m/Y",strtotime($valeur->pubDate)).' - <a href="'.$valeur->link.'">'.utf8_decode($valeur->title).'</a>';
        $ret.= '<br/>'.utf8_decode($valeur->description).'</p>';
    }
    return $ret;
    
}

function parser_simplexml_1($url) {
    $handle = fopen($url, "rb");
    $flux = '';

    if (isset($handle) && !empty($handle)) {
        while (!feof($handle)) {        	
            $flux .= fread($handle, 4096);
        }
    }
    
    //$flux=str_replace(chr(hexdec('130')),'e', $flux);
    
    $RSS2Parser = simplexml_load_string($flux);
    $racine = $RSS2Parser->channel;
    
    foreach($racine->item as $element) {
    
        $news_title = utf8_decode((string)$element->title);
        $news_uri = utf8_decode((string)$element->link);
        $news_desc = utf8_decode((string)$element->description);
        $news_date = utf8_decode((string)$element->pubDate);
        $news_htmldesc = utf8_decode((string)$element->description);
        
    }   

    $ret='';

}

function parser_simplexml_2($url) {
    
    if($flux = simplexml_load_file($url))
    {
       get_rss_string($flux);
    }    
    return $ret;    
}


function parser_fichier_txt() {
    $content=file_get_contents("datas/all.news");
    $tab=explode("----------",$content);
    $ret='<p>les derni&egrave;res news<p>';    
    
    foreach ($tab as $news) {
        $ret.='<p class="contentnews">'.str_replace("\n","<br>",trim($news))."<p>";
    }
    return $ret;    
}


function parser_iframe_facebook($url) {
    return '<iframe src="//www.facebook.com/plugins/follow?href=https%3A%2F%2Fwww.facebook.com%2Ftstd.machris&amp;layout=standard&amp;show_faces=true&amp;colorscheme=light&amp;width=450&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>';
    
}

/*function parser_rsslib($url) {
    return RSS_Display($ret, 15,true);
}*/

spl_autoload_register(function ($clname) {
    $pathtoload=str_replace("\\", "/", $clname);
    include $pathtoload.'.php';
});


use PicoFeed\Reader\Reader;
use PicoFeed\PicoFeedException;

function parser_picofeed($url) {

    try {
        $ret='';
        $reader = new Reader;

        // Return a resource
        $resource = $reader->download($url);

        // Return the right parser instance according to the feed format
        $parser = $reader->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );

        // Return a Feed object
        $feed = $parser->execute();
        /*echo "<p>".utf8_encode($feed->title)."</p>\n";
        echo "<p>".$feed->url."</p>\n";*/
        
        //print_r($feed->items);
        foreach( $feed->items as $item) {
            $ret.='<div class="contentnews">';
            //$ret.= '<p><a href="'.$item->url.'" target="_blank" >'.utf8_encode(strftime("%A, %d %B %Y %H:%M",$item->updated)).'</a></p><br/>';
            $ret.= '<p><a href="'.$item->url.'" target="_blank" >'.$item->getDate()->format("d/m/Y H:i:s").'</a></p><br/>';
            $ret.= $item->content.'</p>';
            $ret.="</div>";
        }  

    }   
    catch (PicoFeedException $e) {
        // Do Something...
        //return "<p>Error while reading news : ".$e->getMessage()."</p>\n";   
        return "<p></p>";
    }
    return $ret;  

}



/**
 * Get all news 
 */
function get_news() {
    $ret='';

    $url = 'http://www.facebook.com/feeds/page.php?format=rss20&id=284950108189664';
    //$url = 'https://www.facebook.com/feeds/page.php?format=rss20&id=367689903249017';
    //$url = 'datas/news.xml';
    
    //return parser_rsslib($url);
    //return parser_iframe_facebook($url);
    //return parser_fichier_txt();
    //return parser_simplexml_1($url);
    //return parser_simplexml_2($url);
    return parser_picofeed($url);

}

?>
