<?php 



session_start();



// database connection
include('../config/db_connect.php');



$user = $pass = '';
// initilizing errors array
$errors = array('user' => '', 'pass' => '', 'login' => '');


// when submit button is clicked
if(isset($_POST['submit'])){

			


	    // check useruname
		if(empty($_POST['username'])){
			$errors['user'] = 'An user is required';
		} else{
		
			if(!preg_match('/^[a-zA-Z\s]+$/', $_POST['username'])){
				$errors['user'] = 'Invalid username';
			}else {
				$user = mysqli_real_escape_string($conn, $_POST['username']);
			}
		}


		// check password 
		if(empty($_POST['password'])){
			$errors['pass'] = 'A password is required';
		} else{
			
			if(!preg_match('/\A[a-z0-9\s]+\Z/i', $_POST['password'])){
				$errors['pass'] = 'Invalid Password';
			}else {
				$pass = mysqli_real_escape_string($conn, $_POST['password']);
			}
		}

		
            // sql statments for verficiation with database records

			$sql  =  "SELECT * FROM Users WHERE BINARY username  = '$user' and BINARY password = '$pass' ";
			$sql2 =  "SELECT * FROM Admins WHERE BINARY username = '$user' and BINARY password = '$pass' ";
            
            // to store the result of the query
			$result = mysqli_query($conn,$sql);
			 // to save the result of the query as array
			$row    = mysqli_fetch_array($result,MYSQLI_ASSOC);
			// to count the rows in the array
            $count  = mysqli_num_rows($result);

             // to store the result of the query
            $result2 = mysqli_query($conn,$sql2);
            // to save the result of the query as array
			$row2    = mysqli_fetch_array($result2,MYSQLI_ASSOC);
			// to count the rows in the array
            $count2  = mysqli_num_rows($result2);
      
      
            // to check if there any rows in the array
            if($count == 1) {
            	  // saves the username as session variable
       			  $_SESSION['userId']= $user;
       			  // redirects the user to home page in loged as police officer
                  header("location: ../index.php");
           }elseif ($count2 == 1) {
           	      // saves the username as session variable
                  $_SESSION['userId2']= $user;
                  // redirects the user to admin page in loged as admin
             	  header("location: ../admin.php");
           } else{
           	     $errors['login'] = "Your Login Username or Password is Wrong!";
                 //echo "Your Login Name or Password is invalid";
           }
               
         }


 ?>





<!DOCTYPE html>
<html>
<?php include('../templates/header.php'); ?>
<head>
	<title>Login</title>
</head>
<body>

	<section class="container grey-text">
		<form class="white" action="login.php" method="post">
  <div class="imgcontainer">
   
  </div>

  <div class="container">
  
  	<p>
   <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" value="<?php echo htmlspecialchars($user) ?>" required>
    <div class="red-text"><?php echo $errors['user']; ?></div>
  	</p>
    
    <p>
	<label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" value="<?php echo htmlspecialchars($pass) ?>" required>
    <div class="red-text"><?php echo $errors['pass']; ?></div>
    </p>
    
    <div class="red-text"><?php echo $errors['login']; ?></div>
    
  	<p>
	<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
	</p>
    
  </div>

 
</form>

	</section>

</body>

<?php include('../templates/footer.php'); ?>
</html>






	