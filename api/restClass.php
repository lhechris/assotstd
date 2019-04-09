<?php
include_once("log.php");

class container {
    private $key;
    private $callable;
    private $childs;
    private $args;


    public function __construct($key) {
        $this->key=$key;
        $this->callable=null;
        $this->childs=array();
        $this->args=array();
    }

    public function add($keys,$callable) {
        if ($keys[0]==$this->key) {
            if (count($keys)==2){
                if (substr($keys[1],0,1)=="{") {
                    array_push($this->args,substr($keys[1],1,-1));
                    $this->callable=$callable;
                    return;
                }
            }
            if (count($keys)==1){
                $this->callable=$callable;
                return;
            }
            foreach($this->childs as $child) {
                if ($child->key==$keys[1]) {

                    $child->add(array_slice($keys,1),$callable);
                    return;
                }
            }
            //not found
            $cont=new container($keys[1]);
            $cont->add(array_slice($keys,1),$callable);
            array_push($this->childs,$cont);
        }
    }

    public function run($turi,$method) {
        trace_info("container->run(".implode(",",$turi).")");
        
        if ($turi[0]==$this->key) {
            if ($this->callable!=null) {
                if (is_callable($this->callable)) {
                    $args=array();
                    for ($i=0;($i<count($this->args))&&($i<(count($turi)-1));$i++) {
                        $args[$this->args[$i]]=$turi[$i+1];
                    }
                    
                    call_user_func($this->callable,$args,$method);
                }else {
                    trace_info("la fonction n'est pas callable");
                }
                return;

            } else if (count($turi)>1){
                foreach($this->childs as $child) {
                    //trace_info($child->key." ".$turi[1]);
                    if ($child->key==$turi[1]) {
                        $child->run(array_slice($turi,1),$method);
                        return;
                    }
                }
                trace_info($turi[1]." not found");
                header("HTTP/1.1 404 Not Found");
                return;
            }
            trace_info("pas de fonction callable definie pour ".$turi[0]);
            header("HTTP/1.1 404 Not Found");
            return;           
        }
        trace_info($turi[0]." not found");
        header("HTTP/1.1 404 Not Found");
        return;
    }

    public function disp() {
        print("KEY=".$this->key."\n");
        print("V=".$this->callable."\n");
        print("args=".print_r($this->args,true)."\n");
        foreach($this->childs as $child) {
            $child->disp();
        }
        print("\n\n");
    }

}

class restClass {

    private $containerget;
    private $containerpost;
    private $root;
    
    public function __construct($root) {
        $this->containerget=new container("");
        $this->containerpost=new container("");   
        $this->root=$root;     
    }

    public function get($pattern,$callable) {
        $ep=explode("/",$pattern);
        $this->containerget->add($ep,$callable);
    }

    public function post($pattern,$callable) {
        $ep=explode("/",$pattern);
        $this->containerpost->add($ep,$callable);
    }

    public function run() {
        if ($_SERVER["REQUEST_METHOD"]=="GET") {
            $uri=$_SERVER["REQUEST_URI"];               
            $turi=explode("/",$uri);                
            if ((count($turi)>=2) && ($turi[1]==$this->root)) {
                $this->containerget->run(array_merge(array(""),array_slice($turi,2)),$_GET);                
            } else {
                header("HTTP/1.1 403 Bad request");  
            }
        } else if ($_SERVER["REQUEST_METHOD"]=="POST") {
            $uri=$_SERVER["REQUEST_URI"];
            $turi=explode("/",$uri);                
            if ((count($turi)>=2) && ($turi[1]==$this->root)) {
                $this->containerpost->run(array_merge(array(""),array_slice($turi,2)),$_POST);                    
            } else {
                header("HTTP/1.1 403 Bad request");  
            }
        } else {
            header("HTTP/1.1 405 Method not allowed");
        }
    }
}
?>