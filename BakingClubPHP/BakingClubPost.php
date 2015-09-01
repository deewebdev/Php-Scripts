<?php

   //the title that will appear in the Windows window bar
   $pagetitle = 'The Baking Club: Post a message';
   //include the standard JEdward page header
   include('BakingClubHeader.html');
   //the menu button that will be highlighted, showing where the user is
   $thispage = 'boards';
   //the standard bar of menu buttons
   include('BakingClubMenu.php');

   /* *********************************************************
      Main content of the page
      *********************************************************
   */
   //check that the session has been properly started by a sign-in
   //if so, sets the $userid variable; otherwise kills the script
   include_once('sessionCheck.php');

   //connect to the database
   require_once('connectDB.php');

   $query = 'SELECT board_name
             FROM boards
             WHERE board_id=' . $_GET['board'];
   $result = mysqli_query($dbc, $query);
   if ($result)
   {
      $row = mysqli_fetch_array($result);
      echo '<p><Strong>Message Boards</strong> </p>';
      echo '<p>Board: <a href="BakingClubBoard.php?board='
                      . $_GET['board'] . '">'
                      . $row['board_name'] . '</a></p>';
   }

   //this will tell us whether the input data was valid
   $validinput = true;

   //if this is true at the end, then show the form to the user
   //but if it is false, we won't show it
   $show_form = false;

      
   //initialise the values that the user will give us
   $subject = '';
   $body = '';
   
   //copy any values we got from $_GET
   if (isset($_GET['board']))
   {
      $board = $_GET['board'];
   }
   if (isset($_GET['thread']))
   {
      $thread = $_GET['thread'];
   }
   if (isset($_GET['msg']))
   {
      $msg = $_GET['msg'];
   }
   
/*   if submit was posted
      if the post is invalid
         tell the user
         set input to be invalid
         prepare to show the form
      else
         write the message to the database
         
   if we have to show the form
      if we get a message id
         retrieve the message id, parent id, forum id, user name, subject, body
         format as a string
      else
         create a text input box for a new subject
      create a text box for the body
*/   
   //check the value of the submit field - 
   //if it is already set, the user must have submitted the form
   
   
   if (isset($_POST['submit']))
   {
      //read and store the data from the form
      if (isset($_GET['msg']))
      {
         $query = 'SELECT subject FROM threads WHERE thread_id="' 
                  . $_GET['thread'] . '"';
         $result = mysqli_query($dbc, $query);
         $row = mysqli_fetch_array($result);
         $subject = $row['subject'];
         mysqli_free_result($result);         
      }
      else
      {
         $subject = trim($_POST['subject']);
      }
      $body = trim($_POST['body']);

      if ($validinput == true)
      {
         if (isset($_GET['msg']))
         {
            $query = 'INSERT INTO messages 
                      (thread_id, userid, date, body)
  	              VALUES ('
                      . '"' . $_GET['thread'] . '",'
                      . '"' . $userid . '",'
                      . 'NOW(),'
                      . '"' . $body . '")';
         }
         else
         {
            $query = 'INSERT INTO threads
                      (board_id, subject, userid)
                      VALUES ('
                      . '"' . $_GET['board'] . '",'
                      . '"' . $subject . '",' 
                      . '"' . $userid .  '")';
            mysqli_query($dbc, $query);
            $newthread = mysqli_insert_id($dbc);

            $query = 'INSERT INTO messages 
                      (thread_id, userid, date, body)
  	              VALUES ('
                      . '"' . $newthread . '",'
                      . '"' . $userid . '",'
                      . 'NOW(),'
                      . '"' . $body . '")';
         }
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
            echo '<p>Thank you</p>';
         } //end of else valid result
         @mysqli_free_result($result);
      } //end of if validinput
      else //so input was not valid
      {
         $show_form = true;
      }      

   } //end of if $_POST['submit'];
   else //so nothing submitted yet
   {
      $show_form = true;
      if (isset($_GET['msg']))
      {
         $query = 'SELECT body
                   FROM messages 
                   WHERE msg_id="' . $_GET['msg'] . '"';
         $result = mysqli_query($dbc, $query);
         $row = mysqli_fetch_array($result);
         mysqli_free_result($result);   
         $body = $row['body'];
      }
   }
   
   //$show_form = true;
   if ($show_form == true)
   {
      //either there was no data submitted
      //or the data wasn't valid
      //so we are showing the form again to get fresh input

      //retrieve the message id, parent id, forum id, user name, subject, body
      //format as a string
 
      if (isset($_GET['msg']))
      {
         $query = 'SELECT m.msg_id, u.fullname, t.subject, m.body
             FROM messages AS m INNER JOIN MEMBERS AS u INNER JOIN threads AS t
                   ON m.userid=u.userid AND m.thread_id=t.thread_id
                   WHERE m.msg_id="' . $_GET['msg'] . '"';
         $result = mysqli_query($dbc, $query);
         $row = mysqli_fetch_array($result);
         mysqli_free_result($result);   

         $filename = 'BakingClubPost.php?board=' 
                  . $board . '&thread='
                  . $thread . '&msg='
                  . $msg;
      }
      else
      {
         $filename = 'BakingClubPost.php?board='
                     . $board;
      }      
      
      //this time we do the form from in php

      echo '<form action="' . $filename . '" method = "post">';

      if (isset($_GET['msg']))
      {
         echo '<p>User: ' . $row['fullname'] . '</p>';
         echo '<p>Subject: ' . $row['subject'] . '</p>';
         echo '<p>Body: ' . $row['body'] . '</p>';
      }
      else 
      {
         echo '<p>Subject:
                  <input type="text" name="subject" size="20" maxsize="40"
                  value="' . $subject . '"/></p>';         
      }

      echo '<p>Text:<br/>
               <textarea name="body" rows="3" cols="40">' . $body 
               . '</textarea></p>';
   
      echo '<p><input type="submit" name="submit" value="Submit" /></p>'
           . '</form>';
      //back into php, and closing the bracket for if($show_form == true)
   }

   /* *********************************************************
      End of main content of the page
      *********************************************************
   */
   
   //include the standard footer information
   include('BakingClubFooter.html');
?>
