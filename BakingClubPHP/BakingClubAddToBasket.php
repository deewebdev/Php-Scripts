<?php

   //buffer the output, since we may need to redirect
   ob_start();

   //the title that will appear in the Windows window bar
   $pagetitle = 'Baking Club: Add to Basket';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'shop';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   //make sure the user is signed in
   //if so, this will set the variable $userid
   //if not, this will quit.
   
   
   include_once('sessionCheck.php');

   //if the user asked to cancel the addition to the basket
   //redirect to the catalogue
   if (isset($_POST['cancel']))
   {
      header('Location: BakingClubShop.php' );
   }

   //if I didn't get a product id, then I shouldn't be here, so quit
   //otherwise, remember the product id
   if (!isset($_POST['productid']))
   {
      echo '<p>ERROR: no item selected</p>';
      include('BakingClubFooter.html');
      ob_end_flush();
      exit();
   }
   else
   {
      $productid = $_POST['productid'];
   }

   //we have established that we are at this page for the right reasons.
   //if the user was sent here from the shop, we need to show them the
   //product details, ask them to say how many items they want, and
   //ask them to add them to their basket, or cancel
   //if the user has already been to this page, but didn't specify a
   //positive number we need to tell them, and ask them again
   //otherwise, we need to insert the data into their basket, and
   //redirect them to their basket page.

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   //connect to the database         
   require_once('connectDB.php');
   
   //query the database
   $query = 'SELECT *
             FROM PRODUCTS
             WHERE productid = "' . $_POST['productid'] . '"';
   $result = @mysqli_query($dbc, $query);

   /* OK to do this here, since user must be signed in */
   echo '<p>View your <a href="BakingClubUserOrders.php">order history</a></p>';
   
   //if the result is OK 
   if ($result) {

      $row = mysqli_fetch_array($result);

      //if the add button was clicked, if the number is valid
      //insert the item into the basket, and redirect to view the basket 
      if (isset($_POST['add']))
      {
         //read the number that was typed into the input
         $number = $_POST['number'];
         if ($number > 0)
         {
            $query = 'INSERT INTO BASKET_ITEMS
                      (customerid, productid, quantity)
                      VALUES (' . $userid . ', ' . $productid
                                          . ', ' . $number . ')';
            $result = @mysqli_query($dbc, $query);
            if ($result)
            {
               header('Location: BakingClubBasket.php' );
            }
            else
            {
               echo '<p>ERROR: something wrong with the database</p>';
               ob_end_flush();
               //close the database
               mysqli_close($dbc);
               include('BakingClubFooter.html');
            }
         }
         else
         {
            $errornote = 'The number of items must be greater than 0';
         }
      }
      //otherwise came here from the product catalogue, so don't do
      //anything special - just show the details and ask for input

      //I can now stop buffering the output, since at this point, I
      //know there are no more redirects.
      ob_end_flush();

      //we display the details  - we don't need to check if input is valid,
      //and so on, since if it was valid, we redirected away from here
      echo '<table cellpadding="3">';
      echo '<tr><td>Product id: </td><td>' . $row['productid'] . '</td></tr>';
      echo '<tr><td>Name: </td><td>' . $row['title'] . '</td></tr>';
     echo '<tr><td>Description: </td><td>' . $row['description'] . '</td></tr>';
      echo '<tr><td>Price: </td><td>&euro;' . $row['price'] . '</td></tr>';
      echo '</table>';

      //free up the resource used for the query
      mysqli_free_result($result);

     //now display the form - note two buttons, one to Add to the basket,
     //and one to cancel
?>

   <form action="BakingClubAddToBasket.php" method="post">
      How many? <input type="text" name="number" size="5" maxsize="5"
                       value="1"\>
      <input type="hidden" name="productid" value="<?php echo $productid;?>"/>
      <input type="submit" name="add" value="Add to basket"/>
      <input type="submit" name="cancel" value="Cancel"/>
   </form>

<?php
      if (isset($errornote))
      {
         echo '<p>' . $errornote . '</p>';
      }
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
