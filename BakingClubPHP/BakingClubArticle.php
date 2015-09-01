<?php
   //the title that will appear in the Windows window bar
   $pagetitle = 'Baking Club: Blog';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'text';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   //check that the session has been properly started by a sign-in
   //if so, sets the $userid variable; otherwise kills the script
   //NOTE: this is replacing the cookie check we did last time
   include_once('sessionCheck.php');

   //connect to the database         
   require_once('connectDB.php');

   $query = 'SELECT text
             FROM blog
             WHERE blogid=' . $_GET['text'];
   $result = mysqli_query($dbc, $query);


   //if we got a non-null result, display the table
   if ($result) 
   {
      $number = mysqli_num_rows($result);
      if ($number <1)
      {
         echo '<p>There are no articles on this page yet</p>';
      }

      echo '<p><table cellpadding="3">
                 <tr>
                    <td><strong>Article</strong></td>
                 </tr>';
      //we now want to display each row - we use a while since at this point we
      //dont know how many records we have
      $shade='#ffffff';
      while ($row = mysqli_fetch_array($result)) 
      {
         if ($shade=='#ffffff')
        {
           $shade='#eeeeee';
        }
        else
        {
           $shade='#ffffff';
        }
         echo '<tr bgcolor="' . $shade . '">
                  <td>' . $row['text'] . '</td>
	       </tr>';
      }
      echo '</table></p>';
      //free up the resource used for the query
      mysqli_free_result($result);
   }
   else 
   {
      //something went wrong
      echo '<p class="error">ERROR!</p>';

      echo '<p>' . mysqli_error($dbc) . '</p>';
      echo '<p>' . 'The query was: ' . $query . '</p>';
   }

   //close the database
   mysqli_close($dbc);


   /* *********************************************************
      End of main content of the page
      *********************************************************
   */
   
   //include the standard footer information
   include('BakingClubFooter.html');
?>
