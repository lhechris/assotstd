<?php
/**
 * objet de base 
 */
 
class component {
        public $id;
        public $pid;
        public $name;
        public $img;
        public $imgover;
        public $link;   
    
        function component($id,$pid,$name) {
            $this->id=$id;
            $this->pid=$pid;
            $this->name=$name;
            $this->display_menulist=true;
        }
        
        function droite() {
            return "";
        }
}
 

 
 
?>