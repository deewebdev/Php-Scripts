<?php

   //defines the path of the folder which will contain the images
   require_once('imagespath.php');

   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: Blog';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'blog';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   //check that the session has been properly started by a sign-in
   //if so, sets the $userid variable; otherwise kills the script
  // include_once('SessionCheckAdmin.php');
   

   /* *********************************************************
      Main content of the page
      *********************************************************
   */
   echo '<h1><center>The Baking Club Blog</center></h1>';

         
   //connect to the database         
   require_once('connectDB.php');

   //the number of records to display on one page
   $display = 3;
   
   //work out how many records there are
   //but if we already know, simply set the variable
   if (isset($_GET['pages']) && is_numeric($_GET['pages'])) 
   {
      $pages = $_GET['pages'];
   }
   else //have to obtain the number from the database
   {
      $query = 'SELECT COUNT(blogid) FROM blog';
      $result = @mysqli_query($dbc, $query);
      $row = @mysqli_fetch_array($result);
      $records = $row[0];
      
      //echo '<p>I think there are ' . $records . ' records</p>';

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
   //echo '<p>There are ' . $pages . ' pages</p>';

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
    //now work out what order the data is to be displayed in
   if (isset($_GET['sort']))
   {
      $sort = $_GET['sort'];
   }
   else
   {
      $sort = 'num';
   }

   switch ($sort)
   {
     
      default:
         $orderby = 'date DESC ';
   }
   
   //echo '<p>We are going to start the display from record ' . $start . '</p>';

   //query the database to get the records
   $query = 'SELECT blogid, date, text, title, photo 
             FROM blog 
             ORDER BY ' . $orderby 
             . 'LIMIT ' . $start . ', ' . $display;
   $result = @mysqli_query($dbc, $query);

   if ($result) {
      $shade = '#ffffff';
      echo '<p><center><table cellpadding="3">
              <tr>
	             <td align="left"><strong><a href="BakingClubBlog.php?sort=num">Date</strong></td>
	             <td align="left"><strong>Title</strong></td>
	             <td align="left"><strong>Photo</strong></td>
	          </tr>';
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
	              <td>' . $row['date'] . '</td>
	              <td><a href="BakingClubArticle.php?text=' 
	              		. $row['blogid'] . '">' . $row['title'] . '</a></td>
	              
	              <td><img src="' . IMAGEPATH . $row['photo'] . '" 
                            height="200"
                            alt=" photo"/></td>
	           </tr>';
         //echo '<p>Should have included filename ' . $row['photo'] . '.</p>';
      }
      echo '</center></table></p>';

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
         echo '<a href="BakingClubBlog.php?start=' . ($start - $display) .
 	                                         '&pages=' . $pages .
					    '">Previous</a> ';
      }

      //show a list of all numbered pages
      //Note: this may be too long a list, and so should only show 10 of them
      for ($i=1; $i <= $pages; $i++) 
      {
         if ($i != $current_page) 
         {
            echo '<a href="BakingClubBlog.php?start=' 
                                 . (($display * ($i - 1))) .
			                     '&pages=' . $pages .
			                     '">' . $i . '</a> ';
         }
	     else 
         {
            echo $i . ' ';
         }
      }

      //if the current page is not the last, then we need a nextbutton
      if ($current_page != $pages) 
      {
         echo '<a href="BakingClubBlog.php?start=' 
                              . ($start + $display) .
 	                          '&pages=' . $pages .
					    '">Next</a>';
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
