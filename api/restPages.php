<?php
include_once("daoPages.php");
include_once("utils.php");


function init_restpages($app){

    $app->get("/page/num/{name}",function($args,$body){
        
        $daopages=new daoPages();
        $ret=$daopages->get($args["name"]);
        echo $ret;

    });

    $app->get("/page/list",function($args,$body){
        
        $daopages=new daoPages();
        $ret=$daopages->getList();
        header("Content-Type: application/json;charset=utf-8");
        echo json_encode($ret);

    });

    $app->post("/page/{name}",function($args,$body){
        if (isregister()) {
            trace_info("POST page update");
            $data = $body;
            $daopages=new daoPages();
            if ($page=$daopages->update($data["id"],$data["texte"])==TRUE) {
                echo("page sauvee");
            } else {
                echo("page non sauvee");
            }            
        } else {
            header("HTTP/1.1 403 Vous n'êtes pas authorisé");
        }
    });





}

?>