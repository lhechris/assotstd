<?php

require_once("core/page.php");
require_once("core/pay_api.php");

$id=0;

if (isset($_GET['buy'])) {
    paypal_buy_photos();
    
} else {

    if (isset($_GET['page'])){
        $id=intval($_GET['page']);
    }
    run_tstd($id);
}

?>
