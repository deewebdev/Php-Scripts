<?php
   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: Message Boards';
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

   $query = 'SELECT b.board_name, t.subject
             FROM boards AS b INNER JOIN threads AS t
             ON b.board_id = t.board_id
             WHERE b.board_id=' . $_GET['board'] . '
             AND t.thread_id=' . $_GET['thread'];
   $result = mysqli_query($dbc, $query);
   if ($result)
   {
      $row = mysqli_fetch_array($result);
      echo '<p><Strong>Message Boards</strong></p>';
      echo '<p>Board: <a href="BakingClubBoard.php?board='
                      . $_GET['board'] . '">'
                      . $row['board_name'] . '</a></p>'; 
      echo '<p>Thread: ' . $row['subject'] . '</p>'; 
   }

   //query the database to get the records
   $query = 'SELECT m.msg_id, m.thread_id, u.userid, u.fullname, m.date, m.body
             FROM messages AS m INNER JOIN MEMBERS AS u 
             ON m.userid = u.userid
             WHERE m.thread_id=' . $_GET['thread'] . '
             ORDER BY msg_id ASC ';
   $result = mysqli_query($dbc, $query);

   //if we got a non-null result, display the table
   if ($result) 
   {
      echo '<p><table cellpadding="3">
                 <tr>
                    <td><strong>ID</strong></td>
                    <td><strong>User</strong></td>
                    <td><strong>Date</strong></td>
                 </tr>';
      //we now want to display each row - we use a while since at this point we
      //dont know how many records we have
      while ($row = mysqli_fetch_array($result)) 
      {
         $shade='#ffffff';
         echo '<tr bgcolor="' . $shade . '">
                  <td>' . $row['msg_id'] . '</td>
                  <td><a href="BakingClubUserMsg.php?userid=' 
                         . $row['userid'] . '">' 
                         . $row['fullname'] . '</a></td>
	          <td>' . $row['date'] . '</td>
                  <td><a href="BakingClubPost.php?board=' 
                         . $_GET['board'] . '&thread='
                         . $_GET['thread'] . '&msg='
                         . $row['msg_id'] . '">Reply</a></td>
	       </tr>';
         $shade='#eeeeee';
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
