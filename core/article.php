<?php

require_once("core/base.php");


/**
 * Recupere les information de l'article
 */
function get_article($name,$pageindex) {
    $content=file_get_contents("datas/".$name.".txt");
  
    if ($content===FALSE) { 
        $content="can't open file";
    } else {
        apply_plugins($content,$pageindex);
    }
    return $content;    
}

class article extends component{

    public $img;

    function article($id,$pid,$name,$img) {
        $this->img = $img;
        parent::__construct($id,$pid,$name);
    }
    
    function display() {
        return get_article($this->name,$this->id);
    }
}

$articles=array(
    new article(100,6 ,'association','article_histo.png'),
    new article(101,7 ,'team',       'article_team.png'),
    new article(102,8 ,'objectif',   'article_objectif.png'),
    new article(103,9,'helpus',      'article_helpus.png'),
    new article(104,10,'concours',   'article_concours.png'),  
    
    new article(105,11,'prj_sene',       'article_prj_sene.png'),
    new article(106,12,'sej_hum_sene',   'article_sej_hum_sene.png'),
    new article(107,13,'rep_adedvi',     'article_rep_adedvi.png'),
    new article(108,14,'rep_tdm',        'article_rep_tdm_2012.png'),
    new article(109,15,'solidarity_show','article_solidarity_show.png'),
    new article(110,16,'gospel',         'article_gospel.png'),
    new article(111,17,'poker',          'article_poker.png'),
    new article(112,18,'spectacle',      'article_spectacle.png'),
    new article(113,19,'village_assoc',  'article_village.png'),
    
    new article(114,22,'projets',        "article_senegal.png" ),
    new article(115,23,'tdm',            "article_tdm.png"),
    new article(116,24,'inde',           "article_inde.png"),
    new article(117,25,'cameroun',       "article_cameroun.png"),
    new article(124,32,'trek_for_smiles', "article_cameroun.png"),
    new article(123,31,'benin',          "article_cameroun.png"),
    new article(118,26,'partageunmonde', "partageunmonde.png"),
    new article(119,27,'souriredumonde', "souriredumonde.png"),
    new article(120,28,'medias',         "article_medias.png"),
    new article(121,29,'sponsor',        "article_sponsor.png"),
    new article(122,30,'videos',         "videos.png")

    );

?>
