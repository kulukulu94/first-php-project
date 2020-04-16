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
$description ='';
//initilizing errors array
$errors = array('description' => '', 'date' => '');


// check if submit button is clicked
if(isset($_POST['submit'])){
		
					// check description
					if(empty($_POST['description'])){
						$errors['description'] = 'Vehicle Type is required';
					} else{
						$Description = $_POST['description'];
					}

					// check date
					if(empty($_POST['date'])){
						$errors['date'] = 'A date is required';
					} else{
						$Date = $_POST['date'];
						
					}

					// this loop is to extract the details of the selected person from the array
			 		foreach(explode('   ', $_POST['Peop_name']) as $ing) { 
										  $people[]= $ing;		
								}	

					$P_name = $people['0'];
					$P_address = $people['1'];


					// this loop is to extract the selected vehicle from the array
					foreach(explode('   ', $_POST['vehic']) as $ing) { 
										  $vehicle[]= $ing;		
								}	

					$v_type = $vehicle['0'];
					$V_colour = $vehicle['1'];



					if(array_filter($errors)){
						//echo 'errors in form';
					} else {
								// escape sql chars
								$description = mysqli_real_escape_string($conn, $Description);
								$date = mysqli_real_escape_string($conn, $Date);
								$offence = mysqli_real_escape_string($conn, $_POST['offence']);
					            
					            $People_name = mysqli_real_escape_string($conn, $P_name);
					            $People_address = mysqli_real_escape_string($conn, $P_address);

					            $vehicle_type = mysqli_real_escape_string($conn, $v_type);
					            $vehicle_colour = mysqli_real_escape_string($conn, $V_colour);


					            // sql to insert the details of the incident
								$sql = "INSERT INTO Incident(Vehicle_ID,People_ID,Incident_Date,Incident_Report,Offence_ID) VALUES((SELECT Vehicle_ID from Vehicle WHERE Vehicle_type='$vehicle_type' AND Vehicle_colour='$vehicle_colour'),(SELECT People_ID FROM People WHERE People_name='$People_name' AND People_address='$People_address'),'$date','$description',(SELECT Offence_ID from Offence WHERE Offence_description = '$offence'))";

								// save to db and check
								if(mysqli_query($conn, $sql)){
											// success
						                   // sql to insert the details of the owner of the vehicle involded in the incident
										  $sql_ = "INSERT INTO Ownership (People_ID,Vehicle_ID) VALUES ((SELECT People_ID FROM People WHERE People_name ='$People_name' AND People_address = '$People_address'),(SELECT Vehicle_ID FROM Vehicle WHERE Vehicle_type ='$vehicle_type' AND Vehicle_colour='$vehicle_colour' ))";

										  if(mysqli_query($conn, $sql_)){
										  	// if saved correctly redirect user same page
										  	header('Location: add_report.php');
										  }else {
										  	$errors['description'] = 'Report already exists in system';
											//echo 'query error: '. mysqli_error($conn);
											}

									
								} else {
									//echo 'query error: '. mysqli_error($conn);
									$errors['description'] = 'Report already exists in system';
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
			<h4 class="center">File Report</h4> 
			<h6 class="center">ENTER THE DETAILS OF THE REPORT</h6> 
					<form class="white" action="add_report.php" method="POST">
						<p style='font-weight: bold' >Report description: </p>
						<textarea type="text" name="description" value="<?php echo htmlspecialchars($description) ?>"></textarea> 
						<div class="red-text"><?php echo $errors['description']; ?></div>
						<p style='font-weight: bold' >Date</p>
						<input type="date" name="date">

			           	<div>
			           		 <p style='font-weight: bold' >SELECT THE OWNER OR ENTER A NEW PERSON IF DOES NOT EXIST!: </p>
						
							 <?php
							$sql2= "SELECT People_name, People_address from People";
							$results2= mysqli_query($conn, $sql2);
			                    ?> 

			                    <select name='Peop_name' class='select-css' >
			                    	<?php  
			                    	while ($row = mysqli_fetch_array($results2)) {
			                    	
			                    	$x =	$row['People_name'];
			               		    $y =	$row['People_address'];
			                    
									    echo "<option value='$x   $y'>$x $y</option>";
									}
									?>
			                    </select> 	<a href="add_people.php" class="btn brand">New Person</a>
					
						</div>


						
					
					 	
			           	<div>
						 <p style='font-weight: bold' >SELECT THE VEHICLE OR ENTER A NEW VEHICLE IF DOES NOT EXIST!: </p>
							 <?php
							 $sql3= "SELECT Vehicle_type, Vehicle_colour from Vehicle";
							 $results3= mysqli_query($conn, $sql3);
			                    ?> 

			                    <select name='vehic' class='select-css' >

			                    	<?php  
			                    	while ($row2 = mysqli_fetch_array($results3)) {
			                    	
			                    	$x1 =	$row2['Vehicle_type'];
			               		    $y1 =	$row2['Vehicle_colour'];
			                    
									    echo "<option value='$x1   $y1'>$x1 $y1</option>";
									}
									?>

			                    </select> 

						<a href="test.php" class="btn brand">New Vehicle</a>
						</div>


						<div>
								<p style='font-weight: bold' >SELECT THE OFFENCE TYPE: </p>
									 <?php
									$sql4= "SELECT Offence_description  from Offence";
									$results4= mysqli_query($conn, $sql4);
					                    ?> 

					                    <select name='offence' class='select-css' >

					                    	<?php  
					                    	while ($row3 = mysqli_fetch_array($results4)) {
					                    
					                    	$z =	$row3['Offence_description'];
					               		   			                   
											    echo "<option value='$z'>$z</option>";
											}
											?>
					                    </select> 
							
						</div>

				
					
						<div class="center">
							<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
						</div>

						

					</form>
	</section>
</body>

<?php include('templates/footer.php'); ?>
</html>