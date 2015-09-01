<?php

   //check to see if the session user id is set, and if delete it
   //eitherway, redirect to the home page.
   session_start();
   if (isset($_SESSION['userid']))
   {
      $_SESSION = array();
      setcookie('PHPSESSID', '', time()*60*60);
      session_destroy();
      session_start();
   }
   header('Location: BakingClubHome.php' );

?>
