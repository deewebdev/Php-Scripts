<?php
include "GIFEncoder.class.php";
if ($dh = opendir ("frames/")) {
    while (false !== ( $dat = readdir ($dh) ) ) {
           if ($dat != "." && $dat != "..")
              {$frames [] = "frames/$dat";
               $framed [] = 35;}
          }
    closedir ($dh); }
$gif = new GIFEncoder($frames,$framed,0,2,0,0,0,"url");
fwrite (fopen("myanimation.gif","wb"), $gif->GetAnimation());
?>

