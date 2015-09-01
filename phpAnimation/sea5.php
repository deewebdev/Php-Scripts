<?php
$im=imageCreateTrueColor(500,400);
$red = imagecolorallocate($im,255,0,0);
$blue = imagecolorallocate($im,0,0,255);
$white = imagecolorallocate($im,255,255,255);
$cyan = imagecolorallocate($im,102,255,255);
$brown = imagecolorallocate($im,102,51,0);
$grey = imagecolorallocate($im,160,160,160);
$black = imagecolorallocate($im,0,0,0);

imagefill($im,20,20,$blue);

imagefilledrectangle($im,0,0,500,275,$cyan);

imagefilledellipse($im,50,270,100,100,$cyan);
imagefilledellipse($im,150,270,100,100,$cyan);
imagefilledellipse($im,250,270,100,100,$cyan);
imagefilledellipse($im,350,270,100,100,$cyan);
imagefilledellipse($im,450,270,100,100,$cyan);

//sails
$points=array(215,175,215,255,160,255);
imagefilledpolygon($im,$points,3,$white);

$points=array(225,175,285,255,225,255);
imagefilledpolygon($im,$points,3,$white);

//shark
imagefilledellipse($im,130,290,70,190,$grey);

imagefilledellipse($im,130,210,10,10,$black);


//mouth
$points=array(162,220,165,250,135,235);
imagefilledpolygon($im,$points,3,$cyan);

imagesetthickness($im,3);
imageline($im,155,222,135,235,$black);

imagesetthickness($im,3);
imageline($im,135,235,162,248,$black);

//fin
$points=array(97,260,97,305,82,280);
imagefilledpolygon($im,$points,3,$grey);

//tail
$points=array(130,380,145,400,115,400);
imagefilledpolygon($im,$points,3,$grey);


//Jumper

imagefilledellipse($im,180,265,25,60,$red);

//head
imagefilledellipse($im,180,225,30,30,$grey);


//boat
$points=array(150,260,290,265,270,310,170,310);
imagefilledpolygon($im,$points,4,$brown);

imagesetthickness($im,3);
imageline($im,220,265,220,165,$brown);




imageGIF($im,"sea5.gif");

?>


