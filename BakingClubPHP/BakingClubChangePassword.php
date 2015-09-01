<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: Password page';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'account';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   //check that the session has been properly started by a sign-in
   //if so, sets the $userid variable; otherwise kills the script
   include_once('sessionCheck.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */
   echo '<h1>The Baking Club Password Change Page</h1>';

   //this will tell us whether the input data was valid
   $validinput = true;
   
   //if this is true at the end, then show the form to the user
   //but if it is false, we won't show it
   $show_form = false;

   //initialise the values that the user will give us
   $email = '';
   $pass = '';
   $passnew = '';
   $passnew1 = '';
   
   //check the value of the submit field - 
   //if it is already set, the user must have submitted the form
   if (isset($_POST['submit']))
   {
      //read and store the data from the form
      $email = trim($_POST['email']);
      $pass = trim($_POST['pass']);
      $passnew = trim($_POST['passnew']);
      $passnew1 = trim($_POST['passnew1']);
 
      if (empty($email))
      {
         echo '<p>You need to give us you\'re email address before you can change your\'re password</p>';
         $validinput = false;
      }

      if (empty($pass))
      {
         echo '<p>Please verify you\'re old password</p>';
         $validinput = false;
      }

      if (empty($passnew))
      {
         echo '<p>Please verify you\'re old password</p>';
         $validinput = false;
      }

      if (empty($passnew1))
      {
         echo '<p>You didn\'t give us a new password!</p>';
         $validinput = false;
      }
      else if ($passnew != $passnew1)
      {
         echo '<p>Your two passwords didn\'t match!</p>';
         $validinput = false;
      }

      if ($validinput == true)
      {
         //first check the details in the database
         //if the email address is not there, or doesn't
         //have the stated password, then we
         //don't change anything, and we tell the user, 
         //and we display the form again.
         //if we fail to add it, we tell them, and quit
         //if the entry works, then everything is fine
         
         //connect to the database
         require_once('connectDB.php');

         //check the number of users with that address : 
         //if <1, stop registration
         $check = 'SELECT userid FROM MEMBERS WHERE email="' 
                  . $email . '" AND pass="' 
                  . SHA1($pass) . '";';
         //echo '<p>Query will be: ' . $check . '</p>';
         $result = @mysqli_query($dbc, $check);
         $number = mysqli_num_rows($result);

         if ($number < 1) 
         {
	    echo 'Sorry we don\'t recognise that email (<em>'
                 . $email . '</em>) and password';
            @mysqli_free_result($result);     
            $show_form = true;
         } //end of if $number <1
         else //so there must have been (at least) one user who matched
         {
            $row = @mysqli_fetch_array($result);
            $dbuserid = $row['userid'];
            //echo '<p>I think userid = ' . $userid . '</p>';
            @mysqli_free_result($result);     

            if ($dbuserid != $userid) 
            {
               echo '<p>ERROR! You are not signed in as that user!</p>';
               die();
            }
            
            $query = 'UPDATE MEMBERS SET pass=SHA1("' . $passnew
                     . '") WHERE userid="' . $userid . '";';
           //echo '<p>The query will be: ' . $query . '</p>';          
           $result = @mysqli_query($dbc, $query);

           if (!$result) 
           { //it failed
              echo '<h1>System Error!</h1>
	                <p>update failed failed</p>';
             echo '<p>'. mysqli_error($dbc) . '</p>';
             echo 'Query: ' . $query . '</p>';
           } //end of if !$result
           else //so there was a valid result from DB
           {
              //we now know input was valid
              //and we know that the database has accepted the update,
              //so display some feedback to the user
              echo '<p>Password changed.</p>';
           } //end of else valid result
           @mysqli_free_result($result);
         } //end of else at least one user 
         mysqli_close($dbc);         
      } //end of if validinput
      else //so input was not valid
      {
         $show_form = true;
      }      

   } //end of if $_POST['submit'];
   else //so nothing submitted yet
   {
      $show_form = true;
   }

   if ($show_form == true)
   {
         //and now show the form
       
?>

<fieldset>
<form action="BakingClubChangePassword.php" method = "post">

   <p> What is your email address?:
      <input type="text" name="email" size="20" maxsize="30"
                  value="<?php echo $email; ?>"/>
   </p>

   <p> What is your password: 
      <input type="password" name = "pass" size = "10" maxlength="20"/>
   </p>

   <p> Please enter a new password: 
      <input type="password" name = "passnew" size = "10" maxlength="20"/>
   </p>

   <p> Please enter the new password again: 
      <input type="password" name = "passnew1" size = "10" maxlength="20"/>
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
