<?php
   $signoutbtn = '<a href="BakingClubSignOut.php"><img src="SignOut_Button.png" height="50" alt="Sign Out"/></a>';
   $signinbtn = '<a href="BakingClubSignIn.php"><img src="SignIn_Button.png" height="50" alt="Sign In"/></a>';
   $regbtn = '<a href="BakingClubRegn.php"><img src="Register_Button.png" height="50" alt="Sign In"/></a>';
   $homebtn = '<a href="BakingClubHome.php"><img src="Home_Button.png" height="50" alt="Home"/></a>';
   $shopbtn = '<a href="BakingClubShop.php"><img src="Shop_Button.png" height="50" alt="Shop"/></a>';
   $viewbtn = '<a href="BakingClubRecipes.php"><img src="Recipes_Button.png" height="50" alt="Recipes"/></a>';
   $enterbtn = '<a href="BakingClubEnterRecipe.php"><img src="EnterRecipes_Button.png" height="55" alt="Enter Recipes"/></a>';
   $accountbtn = '<a href="BakingClubChangePassword.php"><img src="Account_Button.png" height="50" alt="Account"/></a>';
   $boardsbtn = '<a href="BakingClubMessageBoards.php"><img src="MessageBoards_Button.png" height="55" alt="Message Boards"/></a>';
   $memberbtn = '<a href="BakingClubMembers.php"><img src="Members_Button.png" height="50" alt="Members"/></a>';
   $blogbtn = '<a href="BakingClubBlog.php"><img src="Blog_Button.png" height="50" alt="Blog"/></a>';
   $enterBlog = '<a href="BakingClubEnterBlog.php"><img src="EnterBlog_Button.png" height="50" alt="Enter Blog"/></a>';
  
  //Set Menu Bar for a user
   if (isset($_SESSION['username']))
   {
      $username = $_SESSION['username'];
      echo '<p><small>' . $username . '</small> ';

      $menubuttons = array(
                        'signout' => $signoutbtn, 
                        'home' => $homebtn, 
                        'shop' => $shopbtn,
                        'view' => $viewbtn,
						'recipe' => $enterbtn,
                        'account' => $accountbtn,
                        'boards' => $boardsbtn,
						'blog' => $blogbtn);
      foreach ($menubuttons as $key => $button)
      {
         if ($thispage == $key)
	 {
	    echo ' <strong>' . $button . '</strong>';
	 }
	 else
	 {
            echo ' ' . $button;
         }
      } //end of foreach menubutton
   } 
   //If Administrator signs in set Session for administrator
     else if (isset($_SESSION['adminid']))
   {
       $administrator = $_SESSION['adminid'];

      $menubuttons = array(
                        'signout' => $signoutbtn, 
                        'home' => $homebtn, 
                        'view' => $viewbtn,
                        'boards' => $boardsbtn,
                        'members' => $memberbtn,
						'blog' => $blogbtn,
						'enterBlog' => $enterBlog);
      foreach ($menubuttons as $key => $button)
      {
         if ($thispage == $key)
	 {
	    echo ' <strong>' . $button . '</strong>';
	 }
	 else
	 {
            echo ' ' . $button;
         }
      } //end of foreach menubutton
   }//end of isset($_SESSION...
   
   else //so user has either not logged in, or never registered
   {
      echo '<p>';

      $menubuttons = array(
                        'signin' => $signinbtn, 
                        'regn' => $regbtn, 
                        'home' => $homebtn, 
                        'shop' => $shopbtn,
                        'blog' => $blogbtn);
      foreach ($menubuttons as $key => $button)
      {
         if ($thispage == $key)
	 {
	    echo ' <strong>' . $button . '</strong>';
	 }
	 else
	 {
            echo ' ' . $button;
         }
      } //end of foreach menubutton
   }
   echo '</p>';
   echo '<hr>';
?>
