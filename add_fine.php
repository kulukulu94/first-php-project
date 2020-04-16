<?php


session_start();

//database connection
include('config/db_connect.php');

// check if a admin is logged in 
 if(!isset($_SESSION['userId2']) )
{
    // not logged in
    header('Location: login/login.php');
    exit();
}


// to get the id of the incident from the previous page
if(isset($_GET['id'])){
  $_SESSION['incident_id'] = $_GET['id'];
 
} 
 $incident_id = $_SESSION['incident_id'];




//initilizing variables
$fine_amount = $fine_points = '';
//initilizing errors array
$errors = array('fine_amount' => '', 'fine_points' => '');

	if(isset($_POST['submit'])){
						
						// check fine amount
						if(empty($_POST['fine_amount'])){
							$errors['fine_amount'] = 'fine_amount is required';
						} else{
							$fine_amount = $_POST['fine_amount'];
								if(!preg_match('/^[0-9]*$/', $fine_amount)){
								$errors['fine_amount'] = 'fine_amount must be numbers only';
							}
						}

						// check fine points
						if(empty($_POST['fine_points'])){
							$errors['fine_points'] = 'A fine_points is required';
						} else{
							$fine_points = $_POST['fine_points'];
							if(!preg_match('/^[0-9]*$/', $fine_points)){
								$errors['fine_points'] = 'fine_points must be numbers only';
							}
						}

						if(array_filter($errors)){
							//echo 'errors in form';
						} else {
							// escape sql chars
							$fine_amount = mysqli_real_escape_string($conn, $_POST['fine_amount']);
							$fine_points = mysqli_real_escape_string($conn, $_POST['fine_points']);
							$incident_id = mysqli_real_escape_string($conn, $incident_id);
						
						
							$sql = "INSERT INTO Fines(Fine_Amount,Fine_Points,Incident_ID) VALUES ('$fine_amount','$fine_points','$incident_id') ";
							
							// save to db and check
							if(mysqli_query($conn, $sql)){
								// success
								header('Location: add_fine.php');
							} else {
								echo 'query error: '. mysqli_error($conn);
							}
							}
		}
	

?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>
	<section class="container grey-text">
		<h4 class="center">Add fine </h4>
		<form class="white" action="add_fine.php" method="POST">
			<label style="font-weight: bold;">fine amount</label>
			<input type="text" name="fine_amount" value="<?php echo htmlspecialchars($fine_amount) ?>">
			<div class="red-text"><?php echo $errors['fine_amount']; ?></div>
			<label style="font-weight: bold;">fine points</label>
			<input type="text" name="fine_points" value="<?php echo htmlspecialchars($fine_points) ?>">
			<div class="red-text"><?php echo $errors['fine_points']; ?></div>
			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>

</html>
 ?>