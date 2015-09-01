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
   //NOTE: this is replacing the cookie check we did last time
   include_once('sessionCheck.php');


   //connect to the database         
   require_once('connectDB.php');

   $query = 'SELECT board_name
             FROM boards
             WHERE board_id=' . $_GET['board'];
   $result = mysqli_query($dbc, $query);

   if ($result)
   {
      $row = mysqli_fetch_array($result);
      echo '<p><Strong>Message Boards</strong> </p>';
      echo '<p>Board: ' . $row['board_name'] . '</p>'; 
      echo '<p><a href="BakingClubPost.php?board=' 
               . $_GET['board'] 
               . '">Start a new thread</a>.</p>';      
   }

   //query the database to get the records

$query = 'SELECT t.thread_id, t.subject, u.userid, t.date as date1, u.fullname, m.date
FROM threads as t 
INNER JOIN messages as m
INNER JOIN MEMBERS as u
ON t.thread_id = m.thread_id
AND t.userid = u.userid
WHERE t.board_id =' . $_GET['board'] . ' 
AND m.date = (
   SELECT date
   FROM messages
   WHERE thread_id = t.thread_id
   ORDER BY date DESC
   LIMIT 0,1 )
ORDER BY m.date';

   $result = mysqli_query($dbc, $query);

   //if we got a non-null result, display the table
   if ($result) 
   {
      $number = mysqli_num_rows($result);
      if ($number <1)
      {
         echo '<p>Sorry - no threads have been created on this board yet</p>';
      }

      echo '<p><table cellpadding="3">
                 <tr>
                    <td><strong>Subject</strong></td>
                    <td><strong>Initiated by</strong></td>
                    <td><strong>On date</strong></td>
                    <td><strong>Last post</strong></td>
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
                  <td><a href="BakingClubThread.php?board=' 
                                     . $_GET['board'] . '&thread='
                                     . $row['thread_id'] . '">'
                                     . $row['subject'] . '</a></td>
                  <td><a href="BakingClubUserMsg.php?userid=' 
                                     . $row['userid'] . '">'
                                     . $row['fullname'] . '</a></td>
                  <td>' . $row['date1'] . '</td>
	          <td>' . $row['date'] . '</td>
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
