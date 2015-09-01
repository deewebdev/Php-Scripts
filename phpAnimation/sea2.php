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
$points=array(315,175,315,255,260,255);
imagefilledpolygon($im,$points,3,$white);

$points=array(325,175,385,255,325,255);
imagefilledpolygon($im,$points,3,$white);

imagefilledellipse($im,280,265,25,60,$red);

//head
imagefilledellipse($im,280,225,30,30,$grey);


//boat
$points=array(250,260,390,265,370,310,270,310);
imagefilledpolygon($im,$points,4,$brown);

imagesetthickness($im,3);
imageline($im,320,265,320,165,$brown);


//shark
imagefilledellipse($im,130,380,70,180,$grey);

imagefilledellipse($im,130,305,10,10,$black);

imagesetthickness($im,3);
imageline($im,155,320,135,335,$black);

imagesetthickness($im,3);
imageline($im,135,335,160,330,$black);

$points=array(97,360,97,380,85,370);
imagefilledpolygon($im,$points,3,$grey);

imageGIF($im,"sea2.gif");

?>


