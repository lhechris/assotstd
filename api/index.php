<?php


include_once('restClass.php');

session_start();


function exception_error_handler($severity, $message, $file, $line) {
    
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    trace_error($file."[".$line."]" );
    trace_error($message);
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");



//trace_info("SERVER ".print_r($_SERVER,true));
//trace_info("GET  ".print_r($_GET,true));
//trace_info("POST ".print_r($_POST,true));

include_once("restPages.php");
include_once("restRegister.php");

try {
    $app=new restClass("api");

    init_register($app);
    init_restpages($app);


    // Run app
    $app->run();
    

} catch (Exception $e) {
    trace_info($e->getMessage());
    trace_error($e->getMessage());
    print("<html><body>");
    print(str_replace("\n","<br>",$e->getTraceAsString()));
    print("<br><br>");
    print($e->getMessage());
    print("</body></html>");
    header("HTTP/1.0 500 ");
}



?>
