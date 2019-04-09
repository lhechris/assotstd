<?php

include_once("utils.php");

function init_register($app) {

    $app->get("/register/is", function($args, $body) {

        trace_info("isregister ".strval(isregister()));
        if (isregister()) {
            echo("oui");
        } else {
            echo("non");
        }
    });

    $app->post('/register', function($args, $body) {
        
        $json = $body;
        if (($json["login"]=="admin") && ($json["pass"]=="solidarity!")) {
            trace_info("registration ok");
            $_SESSION["register"]="oui";
            echo("oui");

        } else {
            trace_info("registration nok");
            echo("Mauvais login / mot de passe");
        }
    });

    $app->post("/unregister",function($args, $body) {

        $_SESSION["register"]="non";
        session_destroy();
        echo("success");
    });
}

?>