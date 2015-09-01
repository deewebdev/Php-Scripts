<?php

   /* *********************************************************
      Main content of the page
      *********************************************************
   */
      

   //this will tell us whether the input data was valid
   $validinput = true;

   //if this is true at the end, then show the form to the user
   //but if it is false, we won't show it
   $show_form = false;

   //initialise the values that the user will give us
   $email = '';
   $pass = '';
   
   //initialise an array of error messages for displaying later
   $errors = '';
   $administrator = false;

   //check the value of the submit field - 
   //if it is already set, the user must have submitted the form
      if (isset($_POST['submit']))
      {
         //read and store the data from the form
         $email = trim($_POST['email']);
         $pass = trim($_POST['pass']);
 
         if (empty($email))
         {
            $errors = '<p>You didn\'t provide an email address. Please try again.</p>';
            $validinput = false;
         }

         if (empty($pass))
         {
            $errors =  '<p>You didn\'t enter a password, please try again.</p>';
            $validinput = false;
         }

         if ($validinput == true)
         {
            //if the email and password match the database, 
            //then we can sign them in. 
         
            //open the connection to the database     
            require_once('connectDB.php');
			
			

            //check the number of users with that address : 
            //if <1, don't sign in
            $query = 'SELECT userid, fullname, email FROM MEMBERS 
                     WHERE email="' . $email . '" AND 
                           pass = SHA1("' . $pass . '")';
            $result = @mysqli_query($dbc, $query);
            $number = @mysqli_num_rows($result);
			
			if ($number < 1) 
			{
				//not a member so check if its the administrator
				$query = 'SELECT adminid, fullname FROM admin WHERE email="' .$email . '" AND
							pass = SHA1("' . $pass . '")';
				$result = @mysqli_query($dbc, $query);
				$number = @mysqli_num_rows($result);

					if ($number > 0)  //session start for administrator
					{
						$row = @mysqli_fetch_array($result);
						session_start();
						session_regenerate_id();
						$_SESSION['adminid'] = $row['adminid'];
						$_SESSION['userid'] = $row['fullname']; 
						header('Location: BakingClubEnterBlog.php');
					}
					
					else // not a member or admin
					{
						$administrator = true;
						@mysqli_free_result($result);
						$show_form = true;
					}
				}
				else //they are a member
				{
					//sign in accepted for a member
					$row = @mysqli_fetch_array($result);
					session_start();
					session_regenerate_id();
		                                                                              			//echo '<p>Session id = ' . session_id() . '</p>';
					$_SESSION['userid'] = $row['userid'];
					$_SESSION['username'] = $row['fullname'];
					header('Location: BakingClubHome.php');
					//echo 'Signed In  '. $row['fullname'];
				}
			}
		}
				
			
			
            else //not submitted
   {
      $show_form = true;
   }
	//header dump because redirect/session
   	$pagetitle = 'Baking Club: Sign In';
	include('BakingClubHeader.html');
	$thispage = 'signin';
	include('BakingClubMenu.php');
	
	//error dump
	echo '<p><center>' . $errors . '</center></p>';
	if ($administrator == true)
	{
		echo '<p><center>Your details are incorrect. Please try again.</p></center>';
		
	}
   
   if ($show_form == true)
   {

      //either there was no data submitted
      //or the data wasn't valid
      //so we are showing the form again to get fresh input

      //we temporarily quit php to show the form in html
?>

<fieldset>
<form action="BakingClubSignIn.php" method = "post">

   <p> What is your email address?:
      <input type="text" name="email" size="20" maxsize="30"
                  value="<?php echo $email; ?>"/>
   </p>

   <p> Please enter your password: 
      <input type="password" name = "pass" size = "20" maxlength="20"/>
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
