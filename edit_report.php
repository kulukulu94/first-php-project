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


// to get the id of the incident from the previous page
if(isset($_GET['id'])){   
     $_SESSION['incident_id'] = $_GET['id'];
} 
$inc_id = $_SESSION['incident_id'];

// escape sql chars   
$incident_id  = mysqli_real_escape_string($conn, $inc_id);

// sql to get the details of incident report to edit it
$get_sql = " SELECT * FROM Incident WHERE Incident_ID ='$incident_id' ";

//block to extract current report details
if(mysqli_query($conn, $get_sql)){
						// success
		   				$result = mysqli_query($conn,$get_sql);



				     	//$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
				     	$array = array();
							while($rows = $result->fetch_assoc()){

								  $array[] = $rows;
							 }


				     	//print_r($rows);
				     	$retrived_report = $array[0]['Incident_Report'];
				     	//echo $retrived_report;
				     	$retrived_date   = $array[0]['Incident_Date'];
				     	$p_id  = $array[0]['People_ID'];
				     	$v_id  = $array[0]['Vehicle_ID'];
				     	$o_id  = $array[0]['Offence_ID'];

				     	//sql to get person details
				     	$get_p_sql = " SELECT People_name, People_address FROM People WHERE People_ID = '$p_id' ";
				     	//sql to get vehicle details
				     	$get_V_sql = " SELECT Vehicle_type FROM Vehicle WHERE Vehicle_ID = '$v_id' ";
				     	//sql to get offence type
				     	$get_O_sql = " SELECT Offence_description FROM Offence WHERE Offence_ID = '$o_id' ";
				     	//sql to get details of ownership
				     	$get_Ownership_sql = " SELECT * FROM Ownership WHERE Offence_ID = '$o_id' ";

				     	// execute sql and save results in a variables 
				     	if(mysqli_query($conn,$get_p_sql)){
		                    $get_p_result = mysqli_query($conn,$get_p_sql);


				     	    //$get_P_rows   = mysqli_fetch_all($get_p_result, MYSQLI_ASSOC);

				     	    $get_P_rows = array();
							while($rows = $get_p_result->fetch_assoc()){

								  $get_P_rows[] = $rows;
							 }

				     	    //print_r($get_P_rows);
		                    $Person = $get_P_rows[0]['People_name'];
		                    $Person_address = $get_P_rows[0]['People_address'];


				     	}else{
				     		echo 'query error: '. mysqli_error($conn);
				     	}
				     	// execute sql and save results in a variable 
				     	if(mysqli_query($conn,$get_V_sql)){
				     		$get_v_result = mysqli_query($conn,$get_V_sql);

				     	   // $get_v_rows   = mysqli_fetch_all($get_v_result, MYSQLI_ASSOC);

				     	    $get_v_rows = array();
							while($rows = $get_v_result->fetch_assoc()){

								  $get_v_rows[] = $rows;
							 }

		                    $Vehicle = $get_v_rows[0]['Vehicle_type'];


				     	}else{
				     		echo 'query error: '. mysqli_error($conn);
				     	}

				     	// execute sql and save results in a variable 
				     	if(mysqli_query($conn,$get_O_sql)){
				     		$get_O_result = mysqli_query($conn,$get_O_sql);


				     	    //$get_O_rows   = mysqli_fetch_all($get_O_result, MYSQLI_ASSOC);
				     	    $get_O_rows = array();
				     	    while($rows = $get_O_result->fetch_assoc()){

								  $get_O_rows[] = $rows;
							 }


		                    $Offence = $get_O_rows[0]['Offence_description'];


				     	}else{
				     		echo 'query error: '. mysqli_error($conn);
				     	}

	     		}  else {
					echo 'query error: '. mysqli_error($conn);
					} //end of block to extract current report details



// 
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
				//print_r($people) ;
				$P_address = $people['1'];


				// this loop is to extract the selected vehicle from the array
				foreach(explode('   ', $_POST['vehic']) as $ing) { 
									  $vehicle[]= $ing;		
							}	

				$v_type = $vehicle['0'];
				//print_r($vehicle) ;
				$V_colour = $vehicle['1'];




				if(array_filter($errors)){
					//echo 'errors in form';
				} else {
					// escape sql chars
					$description = mysqli_real_escape_string($conn, $Description);
					$date = mysqli_real_escape_string($conn, $Date);
					$offence = mysqli_real_escape_string($conn, $_POST['offence']);
		            
		            $People_name = mysqli_real_escape_string($conn, $P_name);
		            echo $People_name;
		            $People_address = mysqli_real_escape_string($conn, $P_address);

		            $vehicle_type = mysqli_real_escape_string($conn, $v_type);
		            $vehicle_colour = mysqli_real_escape_string($conn, $V_colour);
			
				
				// update the incident report

				$sql = "UPDATE Incident SET Vehicle_ID = (SELECT Vehicle_ID from Vehicle WHERE Vehicle_type='$vehicle_type' AND Vehicle_colour='$vehicle_colour'), People_ID = (SELECT People_ID FROM People WHERE People_name='$People_name' AND People_address='$People_address') ,Incident_Date = '$date', Incident_Report = '$description', Offence_ID = (SELECT Offence_ID from Offence WHERE Offence_description = '$offence') WHERE Incident_ID ='$incident_id' ";
				


					// save to db and check
					if(mysqli_query($conn, $sql)){
						// success
						// update ownership

						$sql_ = "UPDATE Ownership SET People_ID = (SELECT People_ID FROM People WHERE People_name ='$People_name' AND People_address = '$People_address'), Vehicle_ID = (SELECT Vehicle_ID FROM Vehicle WHERE Vehicle_type ='$vehicle_type' AND Vehicle_colour='$vehicle_colour') WHERE People_ID='$p_id' AND Vehicle_ID = '$v_id' ";

						  if(mysqli_query($conn, $sql_)){
						  	header('Location: incidents.php');
						  }else {
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
						<form class="white" action="edit_report.php" method="POST">
							<p  style='color:blue;'>Current Report description click to edit: </p>
							<textarea type="text" name="description"  value="<?php echo htmlspecialchars($retrived_report); ?>"><?php echo htmlspecialchars($retrived_report); ?></textarea> 
							<div class="red-text"><?php echo $errors['description']; ?></div>
							<p  style="color:blue;">Current Date</p>
							<input type="date" value="<?php echo htmlspecialchars($retrived_date) ?>" name="date">


						
							 <div>
				           	 <p style="color:blue;">VEHICLE MAKE & MODEL: <?php echo htmlspecialchars($Vehicle)?>  </p>
							 <p style='font-weight: bold' >SELECT A NEW VEHICLE TO EDIT OR ENTER A NEW VEHICLE IF DOES NOT EXIST ON THE LIST!: </p>
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
							<a href="add_vehicle.php" class="btn brand">New Vehicle</a>
							</div>



						 	
				           	<div>
				           		 <p style="color:blue;" >OWNER IS: <?php echo htmlspecialchars($Person).' ,   '.htmlspecialchars($Person_address) ?>  </p>
				           		 <p style='font-weight: bold' >SELECT A NEW OWNER TO EDIT THE OWNER OR ENTER A NEW PERSON IF DOES NOT EXIST ON THE LIST!: </p>
							
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
							<p style="color:blue;">OFFENCE TYPE: <?php echo htmlspecialchars($Offence)?> </p>
							<p style='font-weight: bold' >TO EDIT SELECT ANOTHER TYPE OFFENCE: </p>
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