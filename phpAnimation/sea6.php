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
$points=array(165,175,165,255,110,255);
imagefilledpolygon($im,$points,3,$white);

$points=array(175,175,235,255,175,255);
imagefilledpolygon($im,$points,3,$white);


//boat
$points=array(100,260,240,265,220,310,120,310);
imagefilledpolygon($im,$points,4,$brown);

imagesetthickness($im,3);
imageline($im,170,265,170,165,$brown);


//Jumper
imagefilledellipse($im,160,335,60,25,$red);

//head
imagefilledellipse($im,190,335,30,30,$grey);

//shark
imagefilledellipse($im,130,380,70,180,$grey);

imagefilledellipse($im,130,305,10,10,$black);

//mouth
$points=array(157,322,163,350,135,335);
imagefilledpolygon($im,$points,3,$red);

//Mouth Outline
imagesetthickness($im,3);
imageline($im,155,320,135,335,$black);

imagesetthickness($im,3);
imageline($im,135,335,163,350,$black);


//fin
$points=array(97,360,97,390,82,375);
imagefilledpolygon($im,$points,3,$grey);


imageGIF($im,"sea6.gif");

?>




