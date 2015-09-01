<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: Message Boards';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'boards';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');
   //and a separate bar showing the message board header
   echo '<p><strong>Message Boards</strong></p>';
   echo '<hr>';

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   //check that the session has been properly started by a sign-in
   //if so, sets the $userid variable; otherwise kills the script
   include_once('sessionCheck.php');
 
   //connect to the database         
   require_once('connectDB.php');


   //query the database to get the records
   $query = 'SELECT board_id, board_name, contents
             FROM boards 
             ORDER BY board_id ASC';
   $result = @mysqli_query($dbc, $query);

   //if we got a non-null result, display the table
   if ($result) {
      echo '<p><table cellpadding="3">
              <tr>
	             <td align="left"><strong>Board</strong></td>
	             <td align="left"><strong>Contents</strong></td>
	          </tr>';
      //we now want to display each row - we use a while since at this point we
      //don't know how many records we have
      while ($row = mysqli_fetch_array($result)) 
      {
         echo '<tr>
	          <td><a href="BakingClubBoard.php?board=' 
                     . $row['board_id'] . '">' . $row['board_name'] . '</a></td>
	          <td>' . $row['contents'] . '</td>
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
