<?php
   //make sure that the sign-in session has been set - if it hasn't
   //report an error and quit immediately.
   if (!isset($_SESSION['userid']))
   {
      echo '<p>ERROR! You cannot access this page '
           . 'since you are not signed in.</p>';
      die();
   }
   else //get and set the userid, which we now know is set in the cookie
   {
      $userid = $_SESSION['userid'];
   }
?>
