<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'Baking Club: Checkout';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'shop';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   //make sure the user is signed in
   //if so, this will set the variable $userid
   
   include_once('sessionCheck.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   /* Pseudocode
      We know the basket is correct (since we validated it each time 
      something was added or it was updated)
      What we should have is a form to submit the credit card details, and
      confirm, but we are not going to do that (use a 3rd party site, with
      credit handling facilities, and use secure sockets for exchanging
      all information to that site, and with your users - not covered in
      CS5017)
      Once we have the data, we want to create a new order in the orders
      table, and move all the items from the basket in the ordered_items table 
      (and in the real world queue them up for shipping), and tell the user 
      what they have purchased, what they paid (including delivery charges)
      and when it will be delivered. We should give them a tracking number
      which would allow them to log back in to check up on the status of
      their delivery, assuming we are updating a database accordingly.

      So, the pseudocode is:
      Issue a query to insert a new order, including customer id, a
      data and time stamp, and a total price, and request the id of
      that item back to us
      For each item in the basket
         add it to the ordered items table
         delete it from basket_items
         build the output string
      Display the final string.
   */

   //connect to the database         
   require_once('connectDB.php');
   
   //work out how many records there are
   $query = 'SELECT count(b.basketitemid)
             FROM BASKET_ITEMS AS b INNER JOIN PRODUCTS AS p
             ON b.productid = p.productid
             WHERE b.customerid = "' . $userid . '"';
   $result = @mysqli_query($dbc, $query);
   $row = @mysqli_fetch_array($result);
   $records = $row[0];
   mysqli_free_result($result);
   if ($records < 1)
   {
      echo '<p>You have no items in your basket</p>';
      include('BakingClubFooter.html');
      exit();
   }

   //now start a transaction
   mysqli_autocommit($dbc, FALSE);
   
   //create a new completed order (without a total price)
   $query = 'INSERT INTO COMPLETED_ORDERS
             (customerid)
             VALUES
             (' . $userid . ')';

   $result = mysqli_query($dbc, $query);
   
   if (mysqli_affected_rows($dbc) == 1)
   {
      //get the id of that last inserted row
      $orderid = mysqli_insert_id($dbc);

      //prepare the raw query for getting all basket items
      $query = 'SELECT b.basketitemid, p.productid, p.title, b.quantity, p.price
                FROM BASKET_ITEMS AS b INNER JOIN PRODUCTS AS p
                ON b.productid = p.productid
                WHERE b.customerid = "' . $userid . '"';
      $result = @mysqli_query($dbc, $query);
   
      if ($result) 
      {
         //prepare an array of output strings for successful transactions
         $output[] = '<p>View your <a href="BakingClubUserOrders.php">complete order history</a></p>';
         $output[] = '<p>Your order has been processed. You have purchased '
                     . ' the following items: </p>';
 
         //prepare to compute the total of the items in the basket
         $total = 0;
         //and the number of successful queries and basketitems
         $succeeded = 0;
         $items = 0;

         $output[] = '<p><table cellpadding="3">
                       <tr>
	                <td align="left"><strong>ID</strong></td>
	                <td align="left"><strong>Product code</strong></td>
	                <td align="left"><strong>Name</strong></td>
	                <td align="left"><strong>Quantity</strong></td>
	                <td align="left"><strong>Unit Price</strong></td>
	                <td align="left"><strong>Total Price</strong></td>
   	          </tr>';
         $shade = '#ffffff';
         while ($row = mysqli_fetch_array($result)) 
         {
            $shade = '#ffffff';
            $output[] =  '<tr bgcolor="' . $shade . '">
                            <td>' . $row['basketitemid'] . '</td>
	                    <td>' . $row['productid'] . '</td>
	                    <td>' . $row['title'] . '</td>
	                    <td>' . $row['quantity'] . '</td>
	                    <td align="right">&euro;' . $row['price'] . '</td>
                            <td align="right">&euro;' 
                                   . ($row['quantity']*$row['price']) . '</td>
                         </tr>';
            $items = $items + 1;

            //now insert this item into ordered items
            $insertq = 'INSERT INTO ORDERED_ITEMS
                        (productid, ordereditemid, quantity, price)
                        VALUES
                        (' . $row['productid'] . ', ' . $orderid
                           . ', ' . $row['quantity'] . ', ' . $row['price']
                           . ')';
            $insresult = @mysqli_query($dbc, $insertq);
            $succeeded = $succeeded + mysqli_affected_rows($dbc);
            $total = $total + ($row['quantity']*$row['price']);

            //now delete from the basket
            $delquery = 'DELETE FROM BASKET_ITEMS
                         WHERE basketitemid=' . $row['basketitemid'];
            $delresult = mysqli_query($dbc, $delquery);
         }
         $output[] = '<tr bgcolor="' . $shade . '">
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>Total basket price:</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="right">&euro;' . $total . '</td>
               </tr>';
         $output[] = '</table></p>';

         //free up the resource used for the query
         mysqli_free_result($result);

         //now update the order with the total price
         $insertq = 'UPDATE COMPLETED_ORDERS
                     SET date = NOW(), total_price = ' . $total . '
                     WHERE completedorderid = ' . $orderid;
         $insresult = @mysqli_query($dbc, $insertq);

         //if the number of successful inserts doesn't equal the
         //number of items in the basket, rollback the transaction
         //similarly if the update didn't work
         if ($succeeded != $items || !$insresult)
         {
            echo '<p>Number succeeded: ' . $succeeded
                 . ' but number of items: ' . $items . '</p>';
            mysqli_rollback($dbc);
            echo '<p>ERROR: database failure. No order has been recorded.
                     Your account will not be debited. Please visit again
                     soon.</p>';
            include('BakingClubFooter.html');
            exit();
         }
      
      }
      else 
      {
         //something went wrong
         mysqli_rollback($dbc);
         echo '<p class="error">ERROR!</p>';
         echo '<p>' . mysqli_error($dbc) . '</p>';
         echo '<p>' . 'The query was: ' . $query . '</p>';
      }
   }
   else
   {
      echo '<p>ERROR: not the right number of rows affected</p>';
      mysqli_rollback($dbc);
   }

   //if I got this far, then everything worked OK, so commit the transaction
   mysqli_commit($dbc);

   //close the database
   mysqli_close($dbc);

   foreach ($output as $string)
   {
      echo $string;
   }

   /* *********************************************************
      End of main content of the page
      *********************************************************
   */
   
   //include the standard footer information
   include('BakingClubFooter.html');
?>

