<?php 

session_start();


//database connection
include('config/db_connect.php');


if(!isset($_SESSION['userId']) and !isset($_SESSION['userId2']))
{
    // not logged in
    header('Location: login/login.php');
    exit();
}


//initilizing variables
$make = $colour = $licence = $exsits = '';

//initilizing errors array
$errors = array('make' => '', 'colour' => '', 'licence' => '','exsits' => '');


// check if submit button is clicked
if(isset($_POST['submit'])){
		
		// check make & model input 
		if(empty($_POST['make'])){
			$errors['make'] = 'Vehicle Type is required';
		} else{
			$make = $_POST['make'];
				if(!preg_match('/^[a-zA-Z\s]+$/', $make)){
				$errors['make'] = 'Type must be letters and spaces only';
			}
		}

		// check colour
		if(empty($_POST['colour'])){
			$errors['colour'] = 'A colour is required';
		} else{
			$colour = $_POST['colour'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $colour)){
				$errors['colour'] = 'colour must be letters and spaces only';
			}
		}

		// check licence
		if(empty($_POST['licence'])){
			$errors['licence'] = 'At least one licence is required';
		} else{
			$licence = $_POST['licence'];
			if(!preg_match('/^[a-zA-Z0-9_.-]*$/', $licence)){
				$errors['licence'] = 'licence must be letters and numbers only no spaces';
			}
		}


		// this loop is to extract the details of the people selected from the array
		 foreach(explode('   ', $_POST['Peop_name']) as $ing) { 
									  $people[]= $ing;
						}	
		$P_name = $people['0'];
		
		$P_address = $people['1'];
		





		if(array_filter($errors)){
			//echo 'errors in form';
		} else {

				// escape sql chars
				$make 			= mysqli_real_escape_string($conn, $_POST['make']);
				$colour 		= mysqli_real_escape_string($conn, $_POST['colour']);
				$Licence 		= mysqli_real_escape_string($conn, $_POST['licence']);
	            
	            $People_name 	= mysqli_real_escape_string($conn, $P_name);
	            $People_address = mysqli_real_escape_string($conn, $P_address);


				//sql to insert new vehicle details
				$sql = "INSERT INTO Vehicle(Vehicle_type,Vehicle_colour,Vehicle_licence) VALUES('$make','$colour','$licence')";
	             

				// save to db and check
				if(mysqli_query($conn, $sql)){
					//case success
					//SQL  to insert owner details
							  $sql_ = "INSERT INTO Ownership (People_ID,Vehicle_ID) VALUES ((SELECT People_ID FROM People WHERE People_name ='$People_name' AND People_address = '$People_address'),(SELECT Vehicle_ID FROM Vehicle WHERE Vehicle_type ='$make' AND Vehicle_colour='$colour' ))";

							  if(mysqli_query($conn, $sql_)){
							  	header('Location: index.php');
							  }else {
								//echo 'query error: '. mysqli_error($conn);
								$errors['exsits'] = 'VEHICLE Exists!';
								}

					
				} else {
					//echo 'query error: vehicle Exists'. mysqli_error($conn);
				}

			}


			

	} // end POST check

?>







<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<?php include('templates/header.php'); ?>
<body>




    <section class="container grey-text">
				<h4 class="center">Add Vehicle</h4>
				<h6 class="center">ENTER THE DETAILS OF THE NEW VEHICLE!</h6>

					<form class="white" action="add_vehicle.php" method="POST">
						<label style="font-weight:bold">Vehicle Make & Model</label>
						<input type="text" name="make" value="<?php echo htmlspecialchars($make) ?>">
						<div class="red-text"><?php echo $errors['make']; ?></div>
						<label style="font-weight:bold">Vehicle Colour</label>
						<input type="text" name="colour" value="<?php echo htmlspecialchars($colour) ?>">
						<div class="red-text"><?php echo $errors['colour']; ?></div>
						<label style="font-weight:bold">Vehicle Licence</label>
						<input type="text" name="licence" value="<?php echo htmlspecialchars($licence) ?>">
						<div class="red-text"><?php echo $errors['licence']; ?></div>

						<a href="add_people.php" class="btn brand">New Person</a>
					
						<br>

					 	<!-- div for select dropdown -->
			           	<div>
							
							 <?php
							    // code to get people details
							 	$sql2= "SELECT People_name, People_address from People";
								$results2= mysqli_query($conn, $sql2); ?> 

			                    <h6 style="font-weight:bold" >Select Owner</h6>

			                    <select name='Peop_name' class='select-css' >

			                    	<?php 

			                    	while ($row = mysqli_fetch_array($results2)) {
			                    	
			                    	$x =	$row['People_name'];
			               		    $y =	$row['People_address'];
			                    
								    echo "<option value='$x   $y'>$x $y</option>";
									}

									?>

			                    </select> 

						</div>

				
						<div class="center red-text"><?php echo $errors['exsits']; ?></div>
			             <br>
						<div class="center">
							<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
						</div>

						

					</form>
	</section>
</body>

<?php include('templates/footer.php'); ?>
</html>