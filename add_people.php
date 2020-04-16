<?php 

session_start();


//database connection
include('config/db_connect.php');


// check if a user is logged in 
if(!isset($_SESSION['userId']) and !isset($_SESSION['userId2']))
{
    // not logged in
    header('Location: login/login.php');
    exit();
}




//initilizing variables
$people_name = $people_address = $people_licence ='';
//initilizing errors array
$errors = array('people_name' => '', 'people_address' => '', 'people_licence' => '');


// check if submit button is clicked
if(isset($_POST['submit'])){
		
						// check name
						if(empty($_POST['people_name'])){
							$errors['people_name'] = 'name is required';
						} else{
							$people_name = $_POST['people_name'];
								if(!preg_match('/^[a-zA-Z\s]+$/', $people_name)){
								$errors['people_name'] = 'people_name must be letters and spaces only';
							}
						}



						// check people address
						if(empty($_POST['people_address'])){
							$errors['people_address'] = 'A people_address is required';

						} else {
							// if(!preg_match('/\A[a-z0-9\s]+\Z/i', $_POST['people_address'])){
							// 	$errors['people_address'] = 'people_address must be letters, numbers and spaces only';
						}
						 

						// check licence
						if(empty($_POST['people_licence'])){
							$errors['people_licence'] = 'A people_licence is required';

						} else {
							if(!preg_match('/^[a-zA-Z0-9_.-]*$/', $_POST['people_licence'])){
								$errors['people_licence'] = 'people_licence must be letters and numbers only';
							

						}

						if(array_filter($errors)){
							//echo 'errors in form';
						} else {
							// escape sql chars
							$people_name    = mysqli_real_escape_string($conn, $_POST['people_name']);
							$people_address = mysqli_real_escape_string($conn, $_POST['people_address']);
							$people_licence = mysqli_real_escape_string($conn, $_POST['people_licence']);
						

							// create sql
							$sql = "INSERT INTO People(People_name, People_address, People_licence) VALUES('$people_name','$people_address','$people_licence')";

							// save to db and check
							if(mysqli_query($conn, $sql)){
								// success
								header('Location: index.php');
							} else {
								echo 'query error: '. mysqli_error($conn);
							}
							}
					} 
	}// end POST check

?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Add People</h4>
		<form class="white" action="add_people.php" method="POST">
			<label style="font-weight:bold">Enter Person name</label>
			<input type="text" name="people_name" value="<?php echo htmlspecialchars($people_name) ?>">
			<div class="red-text"><?php echo $errors['people_name']; ?></div>
			<label style="font-weight:bold">Enter Address</label>
			<input type="text" name="people_address" value="<?php echo htmlspecialchars($people_address) ?>">
			<div class="red-text"><?php echo $errors['people_address']; ?></div>
			<label style="font-weight:bold">Enter licence</label>
			<input type="text" name="people_licence" value="<?php echo htmlspecialchars($people_licence) ?>">
			<div class="red-text"><?php echo $errors['people_licence']; ?></div>
			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>

</html>