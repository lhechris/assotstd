<?php

function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}

$sFileName = $_FILES['image_file']['name'];
$sFileType = $_FILES['image_file']['type'];
$sFileSize = bytesToSize1024($_FILES['image_file']['size'], 1);

echo <<<EOF
<p>Your file: {$sFileName} has been successfully received.</p>
<p>Type: {$sFileType}</p>
<p>Size: {$sFileSize}</p>

EOF;

$uploads_dir = '/homez.379/machris/www/tstd/uploads';

$fic=$_FILES["image_file"];
$error=$fic["error"];
if ($error == UPLOAD_ERR_OK) {
    $tmp_name = $fic["tmp_name"];
    $name = $fic["name"];
    move_uploaded_file($tmp_name, "$uploads_dir/$name");
}

?>
