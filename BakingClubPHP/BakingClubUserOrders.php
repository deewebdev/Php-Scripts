<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'Baking Club Orders';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'BakingClubUserOrders.php';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   //make sure the user is signed in
   //if so, this will set the variable $userid
   
   include_once('sessionCheck.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   //connect to the database         
   require_once('connectDB.php');
   
   //prepare the raw query for getting all basket items
   $query = 'SELECT o.completedorderid, m.userid, m.fullname,
                    o.date, o.total_price
             FROM COMPLETED_ORDERS AS o INNER JOIN MEMBERS AS m
             ON o.customerid = m.userid
             WHERE m.userid = ' . $userid .
             ' ORDER BY o.date DESC';

   echo '<p>View your <a href="BakingClubBasket.php">basket</a></p>';

   //query the database to get the records
   $result = @mysqli_query($dbc, $query);
   
   if ($result) 
   {
      echo '<table cellpadding="3">
               <tr>
	          <td align="left"><strong>ID</strong></td>
	          <td align="left"><strong>User </strong></td>
	          <td align="left"><strong>Name</strong></td>
	          <td align="left"><strong>Date</strong></td>
	          <td align="left"><strong>Total Price</strong></td>
               </tr>';
      $shade = '#ffffff';
      while ($row = mysqli_fetch_array($result)) 
      {
         $shade = '#ffffff';
         echo '<tr bgcolor="' . $shade . '">
                  <td>' . $row['completedorderid'] . '</td>
	          <td>' . $row['userid'] . '</td>
	          <td>' . $row['fullname'] . '</td>
	          <td>' . $row['date'] . '</td>
	          <td align="right">&euro;' . $row['total_price'] . '</td>
               </tr>';

         //prepare the raw query for getting all basket items
         $subquery = 'SELECT o.ordereditemid, p.productid, p.title,
                             p.price, o.quantity
                      FROM ORDERED_ITEMS AS o INNER JOIN PRODUCTS AS p
                      ON o.productid = p.productid
                      WHERE o.orderid = ' . $row['completedorderid'];

         //query the database to get the records
         $subresult = @mysqli_query($dbc, $subquery);

         if ($subresult) 
         {
            while ($subrow = mysqli_fetch_array($subresult))
            {
               echo '<tr><td>&nbsp;</td><td>&nbsp;</td>';
               echo '<td>' . $subrow['quantity'] . ' ' 
                           . $subrow['title'] . ' at '
                           . $subrow['price'] . '</td></tr>';
            }
            mysqli_free_result($subresult);
         }
         else
         {
            echo '<p>ERROR: that didnt work</p>';
         }
   
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

