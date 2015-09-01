<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'Baking Club Shop';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'shop';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   //if the user is not signed in, warn them they will not be able to add
   //anything to their basket, or purchase anything
   if (!isset($_SESSION['userid']))
   {
      echo '<p><h3>The Baking Club Shop</h3></p>';
			'<p>You are currently not signed in. You may browse our
               catalogue, but you will not be able to add anything to your
               basket, nor purchase anything. Please sign in, or
               register with us.</p>';
   }
   else //get and set the userid, which we now know is set in the cookie
   {
      $userid = $_SESSION['userid'];
      echo '<p>View your <a href="BakingClubUserOrders.php">order history</a></p>';
      echo '<p>View your <a href="BakingClubBasket.php">basket</a></p>';
   }


   //connect to the database         
   require_once('connectDB.php');
   
   //the number of records to display on one page
   $display = 10;
   
   //work out how many records there are
   //but if we already know, simply set the variable
   if (isset($_GET['pages']) && is_numeric($_GET['pages'])) 
   {
      $pages = $_GET['pages'];
   }
   else //have to obtain the number from the database
   {
      $query = 'SELECT COUNT(productid) FROM PRODUCTS';
      $result = @mysqli_query($dbc, $query);
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
   $query = 'SELECT * FROM PRODUCTS';
   $query = $query . ' LIMIT ' . $start . ', ' . $display;

   $result = @mysqli_query($dbc, $query);
   
   if ($result) {
      echo '';
      echo '<p><table cellpadding="3">
              <tr>
	             <td align="left"><strong>ID</strong></td>
	             <td align="left"><strong>Name</strong></td>
	             <td align="left"><strong>Price</strong></td>
	             <td align="left"><strong>Details</strong></td>
	          </tr>';
      while ($row = mysqli_fetch_array($result)) 
      {
         echo '<tr bgcolor="CC99FF">
	          <td>' . $row['productid'] . '</td>
	          <td>' . $row['title'] . '</td>
	          <td>&euro;' . $row['price'] . '</td>
	          <td><form action="BakingClubAddToBasket.php" 
                            method="post">
                         <input type="hidden" name="productid" 
                                value = "' . $row['productid'] . '"/>
                         <input type="submit" name="submit" 
                                value="Details"/></form></td>
               </tr>';
         echo '<tr bgcolor="#ffffff">
                  <td>&nbsp;</td>
                  <td>' . $row['description'] . '</td>';
         echo '</tr>';
      }
      echo '</table></p>';
      //echo '</form>';
      
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
         echo '<a href="BakingClubShop.php?start=' 
                           . ($start - $display) 
                           . '&pages=' . $pages 
                           .  '">Previous</a> ';
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


   /* *********************************************************
      End of main content of the page
      *********************************************************
   */
   
   //include the standard footer information
   include('BakingClubFooter.html');
?>

