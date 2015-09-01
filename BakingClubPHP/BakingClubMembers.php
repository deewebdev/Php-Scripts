<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: Members';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'members';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */
   echo '<h1> The Baking Club Administration</h1>';

         
   //connect to the database         
   require_once('connectDB.php');

   //query the database to get the records
   $query = 'SELECT userid, fullname, email, regDate, age, mail  
             FROM MEMBERS ORDER BY userid';
   $result = @mysqli_query($dbc, $query);

   //if we got a non-null result, display the table
   if ($result) {
      echo '<p><table cellpadding="3">
              <tr>
	             
	             <td align="left"><strong>Id</strong></td>
	             <td align="left"><strong>Full name</strong></td>
	             <td align="left"><strong>Email</strong></td>
	             <td align="left"><strong>Date</strong></td>
	             <td align="left"><strong>Age</strong></td>
	             <td align="left"><strong>Mail</strong></td>
	            
	          </tr>';
      //we now want to display each row - we use a while since at this point we
      //don't know how many records we have
      while ($row = mysqli_fetch_array($result)) 
      {
         //in the first cell, create a url to the DeleteUser script,
         //augmented with the id of the current member
         echo '<tr>
	             
	              <td>' . $row['userid'] . '</td>
	              <td>' . $row['fullname'] . '</td>
	              <td>' . $row['email'] . '</td>
	              <td>' . $row['regDate'] . '</td>
	              <td>' . $row['age'] . '</td>
	              <td>' . $row['mail'] . '</td>
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
