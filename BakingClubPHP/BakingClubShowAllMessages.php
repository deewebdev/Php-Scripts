<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: All Messages';
   //include the standard JEdward page header
   include('jedwardHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'boards';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');
   //and a separate bar showing the message board header
   echo '<p><strong>Message Boards</strong></p>';
   echo '<p><strong>All messages (for administrators only)</strong></p>';

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
   $query = 'SELECT m.msg_id, m.thread_id, b.board_id, b.board_name,'
                  . ' u.userid, u.fullname, t.subject, m.body, m.date
             FROM messages AS m 
                INNER JOIN boards AS b 
                INNER JOIN threads as t
                INNER JOIN MEMBERS as u
             ON m.thread_id = t.thread_id 
                AND t.board_id= b.board_id
                AND m.userid= u.userid
             ORDER BY date ASC';
   $result = mysqli_query($dbc, $query);

   //if we got a non-null result, display the table
   if ($result) {
      echo '<p><table cellpadding="3">
              <tr>
	         <td align="left"><strong>ID</strong></td>
	         <td align="left"><strong>User</strong></td>
	         <td align="left"><strong>Board</strong></td>
	         <td align="left"><strong>Thread</strong></td>
	         <td align="left"><strong>Date</strong></td>
	      </tr>';
      //we now want to display each row - we use a while since at this point we
      //don't know how many records we have
      while ($row = mysqli_fetch_array($result)) 
      {
         $shade='#eeeeee';
         echo '<tr bgcolor="' . $shade . '">
                  <td>' . $row['msg_id'] . '</td>
                  <td><a href="BakingClubUserMsg.php?userid='
                                 . $row['userid'] . '">'
                                 . $row['fullname'] . '</a></td>
                  <td><a href="BakingClubBoard.php?board='
                                 . $row['board_id'] . '">'
                                 . $row['board_name'] . '</a></td>
                  <td><a href="BakingClubThread.php?board='
                                 . $row['board_id'] . '&thread='
                                 . $row['thread_id'] . '">'
                                 . $row['subject'] . '</a></td>
                  <td>' . $row['date'] . '</td>
                </tr>';
           $shade='#ffffff';

         echo '<tr bgcolor="' . $shade . '">
                  <td>&nbsp;</td>
                  <td colspan="4">' . $row['body'] . '</td>
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
