<?php

function isregister() {
    //return true;
    if (array_key_exists("register",$_SESSION)) {
        return $_SESSION["register"]=="oui";
    }else {
        return false;
    }

}

?>