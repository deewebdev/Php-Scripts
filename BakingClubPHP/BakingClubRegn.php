<?php
//the title which appears on the Windows Bar
$pagetitle = 'Baking Club Registration Page';
//include the standard page Header
include('BakingClubHeader.html');
//the page we're on
$thispage = 'BakingClubRegn.php';
//include the Menu Bar
include ('BakingClubMenu.php');

/* *********************************************************
      Main content of the page
      *********************************************************
   */

   //if input is good input
$validinput = true;

//if this is true at the end, then show the form to the member
//but if its false, we will not show it
$showform = false;

//initialise the values the user inputs
$fullname = '';
$email = '';
$email2 = '';
$pass = '';
$pass2 = '';
$age = '';
$mail = NULL;

if (isset($_POST['submit']))
{
	//store this data from the form
	$fullname = trim($_POST['fullname']);
	$email = trim($_POST['email']);
	$email2 = trim($_POST['email2']);
	$pass = trim($_POST['pass']);
	$pass2 = trim($_POST['pass2']);
	$age = trim($_POST['age']);
	
	//for the radio buttons error handling
	if (isset($_POST['mail']))
		{
		$mail = trim($_POST['mail']);
		} else {
		$mail = NULL;
		}

		if (empty($fullname))
		{
			echo '<p>You did not enter your name. </p>';
			$validinput = false;
		}
		
		if (empty($email))
		{
			echo '<p>You did not enter your email address. </p>';
			$validinput = false;
		}
		
		else if ($email != $email2)
		{
			echo '<p> Your email address did not match the first entry. Please type in the correct email address.</p>';
			$validinput = false;
		}
		
		if(empty($pass))
		{
			echo '<p> You did not enter a password. </p>';
			$validinput = false;
		}
		else if ($pass != $pass2)
		{
			echo '<p>Your passwords did not match the first entry. Please type in the correct password.</p>';
			$validinput = false;
		}
		
		if (empty($age))
		{
			echo '<p>You have not entered your age. </p>';
			$validinput = false;
		}
		else if (! is_numeric($age))
		{
			echo '<p>You must enter a numeral for your age. </p>';
			$validinput = false;
		}
		
		if ($mail == NULL)
		{
			echo '<p>You have not told us if you want to receive news from us via email. <p>';
			$validinput = false;
		}
		
		if ($validinput == true)
	{
		
		require_once('connectDB.php');
		
		$check = 'SELECT userid from MEMBERS WHERE email="'
				  . $email . '"';
	 $result = @mysqli_query($dbc, $check);
	 $number = mysqli_num_rows($result);
	 
		
		if ($number > 0)
		{
			echo 'Sorry this email address ' . $email . ' is already registered.';
			mysqli_free_result($result);
			$showform = true;
		}
		else
		{
			$query = 'INSERT INTO MEMBERS
					(fullname, email, pass, regDate, age, mail)
					VALUES ('
					 . '\'' . $fullname . '\','
				 . '\'' . $email . '\','
				 . '\'' . SHA1($pass) . '\', '
				 . 'NOW(),'
				 . $age . ','
				 . '\'' . $mail . '\');';
					
			$result = @mysqli_query($dbc, $query);
			
			 if (!$result) 
	   { //it failed
		  echo '<h1>System Error!</h1>
				<p>registration failed</p>';
		 echo '<p>'. mysqli_error($dbc) . '</p>';
		 
		 echo 'Query: ' . $query . '</p>';
		 
	   }
			else
			{
			
			echo '<p> Hi ' . $fullname . ',</p>';
			
			echo '<p> You told us you are ' . $age . '. You can be a baker at any age!</p>';
		
			if ($mail == 'N')
			{
				echo '<p> You said you didn\'t want to receive any newsletters or updates. But don\'t worry you can check out the Message Board page for recipes or interesting facts.</p>';
			}
			else 
			{
				echo '<p> You have said yes to us sending you newsletters and other interesting information about The Baking Club</p>';
			}
			
			echo '<p>Please have a look at our <a href="BakingClubShop.php">shop</a> for some creative baking or have a look at our <a href="BakingClubMessageBoards.php">Message Board Page</a> and swap recipes with other members!</p>';
			
			}
		 
		}	
	}
	else //the input was not valid
		{
		$showform = true;
		}
	}
	else
	{
	 $showform = true;
	}
	
	if ($showform == true)
	
	{
		
		
	?>

<p><h4><center>Hi Please register with <strong>The Baking Club</strong></center></h4></p>

<fieldset>
<form action="BakingClubRegn.php" method = "post">

	<p><h4>What is your full name?
		<input type = "text" name = "fullname" size = "20" maxsize = "30"
				value ="<?php echo $fullname; ?>"/></p>
	
	<p>What is your email address?
		<input type = "text" name = "email" size ="20" maxsize = "30"
				value ="<?php echo $email; ?>"/></p>
				
	<p>Please confirm your email address
		<input type = "text" name = "email2" size = "20" maxsize = "30"
				value ="<?php echo $email2; ?>"/></p>
	
	<p>Please select a password
		<input type = "password" name = "pass" size = "10" maxlength = "20"/></p>
		
	<p>Please confirm the password you chose
		<input type = "password" name = "pass2" size = "10" maxlength = "20"/></p>
		
	<p>Please enter your age
		<input type = "text" name = "age" size = "5" maxsize = "5"
				value = "<?php echo $age; ?>"/>
				
	<p>Do you wish to receive information from us via email from time to time?
		<input type="radio" name = "mail" value = "N"
				<?php if ($mail == 'N') {echo ' checked ';}?> /> 
				No Thank You </p>
				
		<input type="radio" name = "mail" value = "Y"
				<?php if ($mail == 'Y') {echo ' checked ';}?> />
				Yes Id love to </p>
	
	</h4><p>
		<input type ="submit" name ="submit" value="Submit" />
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
