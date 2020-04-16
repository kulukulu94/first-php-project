<?php 

session_start();

//database connection
include('config/db_connect.php');

// check if a admin is logged in
if(!isset($_SESSION['userId2']))
{
    // not logged in
    header('Location: login/login.php');
    exit();
}





//initilizing variables 
$username = $password = $repassword ='';
//initilizing errors array
$errors = array('username' => '', 'password' => '', 'repassword' => '');



if(isset($_POST['submit'])){
		
		// check username
		if(empty($_POST['username'])){
			$errors['username'] = 'username is required';
		} else{
			$username = $_POST['username'];
				if(!preg_match('/^[a-zA-Z\s]+$/', $username)){
				$errors['username'] = 'username must be letters and spaces only';
			}
		}



		// check password
		if(empty($_POST['password'])){
			$errors['password'] = 'A password is required';

		} elseif(!preg_match('/^[a-zA-Z0-9_.-]*$/', $_POST['password'])){
				$errors['password'] = 'password must be letters and spaces only';
			

		} else {
			// password is confirmed
			if($_POST['password'] != $_POST['repassword']){
   				$errors['repassword'] = 'password does not match!';

		} else {
			$password = $_POST['password'];
		}
		}

		if(array_filter($errors)){
			//echo 'errors in form';
		} else {


			// escape sql chars
			$username = mysqli_real_escape_string($conn, $_POST['username']);
			$password = mysqli_real_escape_string($conn, $_POST['password']);
		

			// create sql
			$sql = "INSERT INTO Users(username, password) VALUES('$username','$password')";

			// save to db and check
			if(mysqli_query($conn, $sql)){
				// success
				header('Location: admin.php');
			} else {
				echo 'query error: '. mysqli_error($conn);
			}
			}
	} // end POST check

?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Add Police Officer Account</h4>
		<form class="white" action="create_police_account.php" method="POST">
			<label style="font-weight: bold;">Enter Username</label>
			<input type="text" name="username" value="<?php echo htmlspecialchars($username) ?>">
			<div class="red-text"><?php echo $errors['username']; ?></div>
			<label style="font-weight: bold;">Enter Password</label>
			<input type="password" name="password" value="<?php echo htmlspecialchars($password) ?>">
			<div class="red-text"><?php echo $errors['password']; ?></div>
			<label style="font-weight: bold;">Confirm Password</label>
			<input type="password" name="repassword" value="<?php echo htmlspecialchars($repassword) ?>">
			<div class="red-text"><?php echo $errors['repassword']; ?></div>
			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>

</html>