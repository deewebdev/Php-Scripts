<?php
   //the title that will appear in the Windows window bar
   $pagetitle = 'BakingClub: Message Boards';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'boards';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   //check that the session has been properly started by a sign-in
   //if so, sets the $userid variable; otherwise kills the script
   include_once('sessionCheck.php');

   //connect to the database         
   require_once('connectDB.php');

   $query = 'SELECT fullname
             FROM MEMBERS 
             WHERE userid=' . $_GET['userid'];
   $result = mysqli_query($dbc, $query);
   if ($result)
   {
      $row = mysqli_fetch_array($result);
      echo '<p><Strong>Message Boards</strong></p>';
      echo '<p>Posts by: ' . $row['fullname'] . ':</p>';      
   }

   //query the database to get the records
   $query = 'SELECT m.msg_id, m.thread_id, b.board_id, b.board_name, t.subject, m.body, m.date
             FROM messages AS m INNER JOIN boards AS b INNER JOIN threads as t
             ON m.thread_id = t.thread_id AND t.board_id= b.board_id
             WHERE m.userid = ' . $_GET['userid'] . '
             ORDER BY date ASC';
   $result = mysqli_query($dbc, $query);

   //if we got a non-null result, display the table
   if ($result) 
   {
      echo '<p><table cellpadding="3">
              <tr>
                 <td align="left"><strong>ID</strong></td>
                 <td align="left"><strong>Board</strong></td>
                 <td align="left"><strong>Thread</strong></td>
                 <td align="left"><strong>Date</strong></td>
              </tr>';

      //we now want to display each row - we use a while since at this point we
      //dont know how many records we have
      while ($row = mysqli_fetch_array($result)) 
      {
           $shade='#eeeeee';
         echo '<tr bgcolor="' . $shade . '">
	              <td>' . $row['msg_id'] . '</td>
	              <td><a href="BakingClubBoard.php?board=' 
                                     . $row['board_id'] . '">' 
                                     . $row['board_name'] . '</a></td>
	              <td><a href="BakingClubThread.php?board=' 
                                     . $row['board_id'] . '&thread='
                                     . $row['thread_id'] . '">' 
                                     . $row['subject'] . '</a></td>
	              <td>' . $row['date'] . '</td>
                      <td><a href="BakingClubPost.php?board=' 
                                     . $row['board_id'] . '&thread='
                                     . $row['thread_id'] . '&msg='
                                     . $row['msg_id'] . '">Reply</a></td>
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
