<?php
include_once("utils.php");

class daoPages {

    private $pagenotfound="<div>Page not found</div>";
    private $repertoire=__DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR."assotstd".DIRECTORY_SEPARATOR."datas";
    //private $repertoire=__DIR__ . DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR."datas";

    public function get($pagenum) {

        $json=$this->readmeta();
        if (! array_key_exists("menus",$json)) {
            return $this->pagenotfound;
        }
        foreach ($json["menus"] as $menu=>$pages) {
            foreach ($pages as $page) {
                if ($page["id"]==$pagenum) {   
                    $filename=$this->repertoire.DIRECTORY_SEPARATOR.$page["filename"];
                    if (file_exists($filename)) {
                        return file_get_contents($filename);
                    } else {
                        return $this->pagenotfound;
                    }            
                }
            }
        }
        return $this->pagenotfound;
    }

    /**
     * Retourne la liste des pages
     */
    public function getList() {

        $ret=array();
        $json=$this->readmeta();

        foreach ($json["menus"] as $menu=>$pages) {
            foreach ($pages as $page) {
                $p=array("id" => $page["id"],"name"=>$page["name"]);
                array_push($ret,$p);
            }
        }
        return $ret;
    }

    /**
     * 
     */
    public function update($num,$texte)
    {
        //Normalement ici on est proteges en access dans restPages
        
        $json=$this->readmeta();
        $pagetoupd=null;
        foreach ($json["menus"] as $menu=>$pages) {
            foreach ($pages as $page) {
                if ($page["id"]==$num) {
                    $pagetoupd=$page;
                    break;
                }
            }
        }
        
        if ($pagetoupd!=null) {
            $filename=$this->repertoire.DIRECTORY_SEPARATOR.$pagetoupd["filename"];
            //verify qu'il y a des modif
            if (file_get_contents($filename)==$texte) {
                trace_info("pas de modification pour ".$pagetoupd["name"].", on ne fait rien");
                return false;
            } else {
                //$this->history($name);
                $hdl=fopen($filename,"w");
                fwrite($hdl,$texte);
                fclose($hdl);
                return true;
            }
        } else {
            return false;
        }
    }


    private function readmeta() {
        $fname=$this->repertoire.DIRECTORY_SEPARATOR."meta.json";
    
        $json=json_decode(file_get_contents($fname),true);
        return $json;
    
    }
    

}

?>