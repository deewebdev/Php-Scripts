<?php

   //get the path to the images folder
   require_once('imagespath.php');
   
   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: Blog';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'blog';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */

   //check that the session started
   //if so, sets the $admin variable; otherwise kills the script
   include_once('SessionCheckAdmin.php');

   //this will tell us whether the input data was valid
   $validinput = true;

   
   //if this is true at the end, then show the form to the user
   //but if it is false, we won't show it
   $show_form = false;

   //initialise the values that the user will give us
   
   //$date = '';
   $title = '';
   $text = '';
   $photo = '';
   
   //check the value of the submit field - 
   //if it is already set, the user must have submitted the form
   if (isset($_POST['submit']))
   {
      //read and store the data from the form
      $title = trim($_POST['title']);
      $text = trim($_POST['text']);
      $photo = $_FILES['photo']['name'];
      echo '<p>The filename is ' . $photo . '.</p>';   
      move_uploaded_file($_FILES['photo']['tmp_name'], IMAGEPATH . $photo);   
      //echo '<p>File location: ' . $_FILES['photo']['tmp_name'] . '</p>';
 
      if (empty($title))
      {
         echo '<p>Please name the article</p>';
         $validinput = false;
      }

      if (empty($photo))
      {
         echo '<p>Please upload a photo</p>';
         $validinput = false;
      }
      else if ($_FILES['photo']['type'] != 'image/gif'
               && $_FILES['photo']['type'] != 'image/jpeg'
               && $_FILES['photo']['type'] != 'image/pjpeg'
               && $_FILES['photo']['type'] != 'image/png'
               && $_FILES['photo']['type'] != 'image/JPG'
               && $_FILES['photo']['type'] != 'image/X-PNG'
               && $_FILES['photo']['type'] != 'image/PNG'
               && $_FILES['photo']['type'] != 'image/x-png')
      {
         echo '<p>The file you submitted had type: ' . $_FILES['photo']['type']
                  . ', which is not a file type we recognise. '
                  . 'We need gif, jpeg '
                  . 'or png. Please try again.</p>';
         $validinput = false;
      }
      else if ($_FILES['photo']['size'] <1 
              || $_FILES['photo']['size'] > $_POST['MAX_SIZE'])
      {
         echo '<p>The size of that image was ' 
              . (int)($_FILES['photo']['size'] / 1024)
              . 'KB. It must be greater than 0 and less than '
              . (int)($_POST['MAX_SIZE'] / 1024) . 'KB. Please try again.</p>';
         $validinput = false;
      }
      echo '<p>(for information) You image is called '
           . $_FILES['photo']['name']
           . ', its type is '
           . $_FILES['photo']['type']
           . ' and its size was '
           . $_FILES['photo']['size']
           . ' bytes (or ' 
           . (int)($_FILES['photo']['size']/1024) 
           . ' Kb).</p>';

      if ($validinput == true)
      {
         //first try to enter the details in the database
         //if the email address is not there, then we
         //don't add the info, and we tell the user, 
         //and we display the form again.
         //if we fail to add it, we tell them, and quit
         //if the entry works, then everything is fine
         //we display message, and tell them to visit our shop.
         
         require_once('connectDB.php');

        // $administrator = $_SESSION['adminid'];;
		$administrator = $_SESSION['adminid'];
         $query = 'INSERT INTO blog 
                   (adminid, title, text, photo)
  	           VALUES ('
                   . '\'' . $administrator . '\','
                   . '\'' . $title . '\','
                   . '\'' . $text .  '\','
                   . '\'' . $photo . '\');';
         $result = @mysqli_query($dbc, $query);

         if (!$result) 
         { //it failed
            echo '<h1>System Error!</h1>
	              <p>update failed failed</p>';
            echo '<p>'. mysqli_error($dbc) . '</p>';
            echo 'Query: ' . $query . '</p>';
         }
         @mysqli_free_result($result);
      } 
      mysqli_close($dbc);         
   }
   else
   {
      $show_form = true;
   }      

   
   if ($show_form == true)
   {
      //either there was no data submitted
      //or the data wasn't valid
      //so we are showing the form again to get fresh input

      //we temporarily quit php to show the form in html
?>

<p><h4>Enter articles for the blog</h4></p>

<fieldset>
<form enctype="multipart/form-data" action="BakingClubEnterBlog.php" method = "post">
 
   <input type="hidden" name="MAX_SIZE" value="524288"/>

   <p> What is the title of your article today?
      <input type="text" name="title" size="20" maxsize="40"
                  value="<?php echo $title; ?>"/>
   </p>

   <p> Upload a photo to complement the article:
      <input type="file" name="photo"/>
   </p>

   <p> Write your article here:<br/>
       <textarea name="text" rows="3" cols="40"><?php echo $text; ?></textarea></p>
   </p>
   
   <p>
      <input type="submit" name="submit" value="Submit" />
   </p>
</form>
</fieldset>

<?php

      //back into php, and closing the bracket for if($show_form == true)
   }

   /* *********************************************************
      End of main content of the page
      *********************************************************
   */
   
   //include the standard footer information
   include('BakingClubFooter.html');
?>
