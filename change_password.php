<?php 

session_start();

//database connection
include('config/db_connect.php');


$police_user = $admin  ='';
// check if a user is logged in and make username a session variable
if(!isset($_SESSION['userId']) and !isset($_SESSION['userId2']))
{
    // not logged in
    header('Location: login/login.php');
    exit();
} elseif (isset($_SESSION['userId'])){
	$police_user = $_SESSION['userId'];
} else {
	$admin       = $_SESSION['userId2'];
}




//initilizing variables    
$old_pass = $password = $repassword ='';
//initilizing errors array
$errors = array('old_pass' => '', 'password' => '', 'repassword' => '');

if(isset($_POST['submit'])){
		
					// check current password
					if(empty($_POST['old_pass'])){
						$errors['old_pass'] = 'A old_pass is required';
					} else{
						
						if(!preg_match('/\A[a-z0-9\s]+\Z/i', $_POST['old_pass'])){
							$errors['old_pass'] = 'Invalid Password format';
						}else {
							$old_pass = mysqli_real_escape_string($conn, $_POST['old_pass']);
						}
					}
					
					// check new password
					if(empty($_POST['password'])){
						$errors['password'] = 'A password is required';

					} elseif(!preg_match('/^[a-zA-Z0-9_.-]*$/', $_POST['password'])){
							$errors['password'] = 'password must be letters and spaces only';
						

					} else {   //check if confirmed password is matching 
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
			   

						$sql  =  "SELECT * FROM Users WHERE BINARY username  = '$police_user' and BINARY password = '$old_pass' ";

						$sql2 =  "SELECT * FROM Admins WHERE BINARY username = '$admin' and BINARY password = '$old_pass' ";
			           
						$result = mysqli_query($conn,$sql);
						$row    = mysqli_fetch_array($result,MYSQLI_ASSOC);
			            $count  = mysqli_num_rows($result);


			            $result2 = mysqli_query($conn,$sql2);
						$row2    = mysqli_fetch_array($result2,MYSQLI_ASSOC);
			            $count2  = mysqli_num_rows($result2);
			      
			      
			            // if count = 1 means there is a user found with this current password
			            if($count == 1) {
			  				//udate police officer password
			                 $sql_update = "UPDATE Users SET password = '$password' WHERE username ='$police_user' ";

			            	 if(mysqli_query($conn, $sql_update)){
							// success
			            	 	header('Location: index.php');
						} else {
							echo 'query error: '. mysqli_error($conn);
						}
			       			 
			           }elseif ($count2 == 1) {
			           	    //update admin password
			           			$sql_update2 = "UPDATE Admins SET password = '$password' WHERE username = '$admin' ";
			           			if(mysqli_query($conn, $sql_update2)){
							// success
			           				header('Location: index.php');
						} else {
							echo 'query error: '. mysqli_error($conn);
						}
			                 
			           } else{
			                 $errors['old_pass'] = 'Current Password is wrong!';
			           }

						}
	} // end POST check

?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Change Your Password</h4>
		<form class="white" action="change_password.php" method="POST">
			<label style="font-weight: bold">Enter Current Password</label>
			<input type="password" name="old_pass" value="<?php echo htmlspecialchars($old_pass) ?>">
			<div class="red-text"><?php echo $errors['old_pass']; ?></div>
			<label style="font-weight: bold">Enter New Password</label>
			<input type="password" name="password" value="<?php echo htmlspecialchars($password) ?>">
			<div class="red-text"><?php echo $errors['password']; ?></div>
			<label style="font-weight: bold">Confirm Password</label>
			<input type="password" name="repassword" value="<?php echo htmlspecialchars($repassword) ?>">
			<div class="red-text"><?php echo $errors['repassword']; ?></div>
			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>

</html>