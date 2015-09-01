<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'Baking Club Basket';
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

   //connect to the database         
   require_once('connectDB.php');
   
   //the number of records to display on one page
   $display = 10;

   //allow user to inspect order history (OK, since user logged in)
   echo '<p>View your <a href="BakingClubUserOrders.php">order history</a></p>';
   //display a link to move from basket to checkout
   echo '<p><a href="BakingClubCheckout.php">Proceed to checkout</a></p>';

   //so far, we have found no input errors
   $valid_input = true;

   //if I was asked to update the basket, check that the data is valid
   //before updating the database
   if (isset($_POST['update']))
   {
      //for each key in the array, which will be an input box name
      foreach($_POST as $key => $input)
      {
         if ($key != 'update')
         {
            //validate this input value 
            if (!is_numeric($input) || $input < 0)
            {
               $valid_input = false;
               $errors[] = $key;
            }
            else if ($input == 0) 
            {
               //Note: this is a bit brutal - we are deleting the item
               //without giving the user a chance to reflect ...
               $query = 'DELETE FROM BASKET_ITEMS
                         WHERE basketitemid = ' . $key;
               $result = @mysqli_query($dbc, $query);
               if (!$result)
               {
                  echo '<p>ERROR: something went wrong with ' . $query . '</p>';
               }
            }
            else
            {
               //change the quantity as asked
               $query = 'UPDATE BASKET_ITEMS
                         SET quantity = ' . $input . '
                         WHERE basketitemid = ' . $key;
               $result = @mysqli_query($dbc, $query);
               if (!$result)
               {
                  echo '<p>ERROR: something went wrong with ' . $query . '</p>';
               }
            }
         }
      }
   }

   //if any of the fields were wrong, give them a chance to update them
   //all, but remember all the valid data
   if ($valid_input == false)
   {
      echo '<p>ERROR: inputs must be non-negative numbers. Please correct: ';
      foreach ($errors as $error)
      {
         echo $error . ' ';
      }
      echo '</p>';
   }
   
   //prepare the query for getting all basket items
   //to make it easier to remember to use the same query for counting
   //records as for retrieving them, I split the qury into multiple parts,
   //and I will re-use the middle part each time
   $countquery = 'SELECT count(b.basketitemid)';
   $rawquery = ' FROM BASKET_ITEMS AS b INNER JOIN PRODUCTS AS p
                 ON b.productid = p.productid
                 WHERE b.customerid = "' . $userid . '"';

   //work out how many records there are
   //but if we already know, simply set the variable
   if (isset($_GET['pages']) && is_numeric($_GET['pages'])) 
   {
      $pages = $_GET['pages'];
   }
   else //have to obtain the number from the database
   {
      $result = @mysqli_query($dbc, $countquery . $rawquery);
      $row = @mysqli_fetch_array($result);
      $records = $row[0];
      
      //now work out how many pages
      if ($records > $display) 
      {
         $pages = ceil($records/$display);
      }
      else 
      {
         $pages = 1;
      }
      mysqli_free_result($result);
   }

   //depending on which page of results we are on, we need to
   //issue a query to the database to return records beginning
   //at a specific row
   //calculate that row and store it in the variable $start
   if (isset($_GET['start']) && is_numeric($_GET['start'])) 
   {
      $start = $_GET['start'];
   }
   else 
   {
      $start = 0;
   }
   
   //query the database to get the records
   //construct the query from the rawquery used above
   $sq = 'SELECT b.basketitemid, p.productid, p.title, b.quantity, p.price ';
   $query = $sq . $rawquery . ' LIMIT ' . $start . ', ' . $display;

   $result = @mysqli_query($dbc, $query);
   
   if ($result) {

      //prepare to compute the total of the items in the basket
      $total = 0;

      echo '<p>To delete an item, simply re-set its quantity to 0</p>';
      echo '<form method="post" action="BakingClubBasket.php">';
      echo '<p><table cellpadding="3">
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
         echo '<tr bgcolor="' . $shade . '">
	          <td>' . $row['basketitemid'] . '</td>
	          <td>' . $row['productid'] . '</td>
	          <td>' . $row['title'] . '</td>
	          <td><input type="text" name="' . $row['basketitemid'] . '"'
                                . 'size="3" maxsize="3" value="' 
                                . $row['quantity'] . '"/></td>
	          <td align="right">&euro;' . $row['price'] . '</td>
                  <td align="right">&euro;' 
                            . ($row['quantity']*$row['price']) . '</td>
               </tr>';
        $total = $total + ($row['quantity']*$row['price']);
      }
      echo '<tr bgcolor="' . $shade . '">
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td>Total basket price:</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td align="right">&euro;' . $total . '</td>
            </tr>';
      echo '</table></p>';
      echo '<input type="submit" name="update" value="Update"/>';
      echo '</form>';
      
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

   //now make the links to other pages of results, if there are any
   if ($pages > 1) 
   {
      //find the current page
      $current_page = ($start/$display) + 1;
      //echo '<p>I think the current page is ' . $current_page . '</p>';
      //if the current page is not the first, then we need a previous button
      if ($current_page != 1) 
      {
         echo '<a href="BakingClubShop.php?start=' . ($start - $display) 
 	                 . '&pages=' . $pages 
			 . '">Previous</a> ';
      }

      //show a list of all numbered pages
      //Note: this may be too long a list, and so should only show 10 of them
      for ($i=1; $i <= $pages; $i++) 
      {
         if ($i != $current_page) 
         {
            echo '<a href="BakingClubShop.php?start=' 
                            . (($display * ($i - 1))) 
			    . '&pages=' . $pages 
			    . '">' . $i . '</a> ';
         }
         else 
         {
            echo $i . ' ';
         }
      }

      //if the current page is not the last, then we need a nextbutton
      if ($current_page != $pages) 
      {
         echo '<a href="BakingClubShop.php?start=' 
                         . ($start + $display) 
 	                 . '&pages=' . $pages 
			 . '">Next</a>';
      }
      echo '</p>';
   } //end if $pages > 1

   echo '<p><a href="BakingClubCheckout.php">Proceed to checkout</a></p>';

   /* *********************************************************
      End of main content of the page
      *********************************************************
   */
   
   //include the standard footer information
   include('BakingClubFooter.html');
?>

