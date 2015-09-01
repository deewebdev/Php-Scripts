<?php
	//title of Page
	$pagetitle = 'The Baking Club';
 
	include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
	$thispage = 'BakingClubHome.php';

	include('BakingClubMenu.php');


	echo '<p><center><h2>Welcome to The Baking Club </h2></center></p>';
	echo '<p><h4>We are baking enthusiasts and if you are like us you have come to the right place.</h4></p>';
	echo '<p>Baking is a fun pass time and its great to share it with other likeminded people...or bakers!</br>
			Being a member of <strong>The Baking Club</strong> allows you to view recipes that other members have uploaded and you can try them out for yourself.</br>
			So sign up and <a href="BakingClubRegn.php">register</a> with us and you can broaden your baking horizons!</p>';

  //include a footer
   include('BakingClubFooter.html');
   
 ?>  
 
 