<?php
    function watermarking($source, $watermark, $save = NULL, $width = null, $height = null) {
        $watermark = @imagecreatefrompng($watermark)
            or exit("Impossible d'ouvrir le fichier (watermark).");
 
        imageAlphaBlending($watermark, false);
        imageSaveAlpha($watermark, true);
 
        $imageString = @file_get_contents($source)
            or exit("Impossible d'ouvrir le fichier (image).");
        $image = @imagecreatefromstring($imageString)
            or exit("Format de fichier (image) inconnu.");
 
        $imageWidth = imageSX($image);
        $imageHeight = imageSY($image);
 
        if (!($width)) {
            $watermarkWidth = imageSX($watermark);
        } else {
            $watermarkWidth = $width;
        }
 
        if (!($height)) {
            $watermarkHeight = imageSY($watermark);
        } else {
            $watermarkHeight = $height;
        }
 
        $coordinateX = ($imageWidth - 5) - ($watermarkWidth);
        $coordinateY = ($imageHeight - 5) - ($watermarkHeight);
 
        imagecopy($image, $watermark, $coordinateX, $coordinateY, 0, 0, $watermarkWidth, $watermarkHeight);
 
        if (!($save)) {
            header('Content-Type: image/jpeg');
        }    
 
        imagejpeg ($image, $save, 100);
 
        imagedestroy($image);
        imagedestroy($watermark);
 
        if (!($save)) {
            exit;
        }
    }
?>