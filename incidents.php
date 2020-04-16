<?php
session_start();

// error_reporting(-1); // reports all errors
// ini_set("display_errors", "1"); // shows all errors
// ini_set("log_errors", 1);
// ini_set("error_log", "/tmp/php-error.log");

//include('login/login.php');
include('config/db_connect.php');

//check if a user is logged in
 if(!isset($_SESSION['userId']) and !isset($_SESSION['userId2']) )
{
    // not logged in
    header('Location: login/login.php');
    exit();
}


//initilizing variables
$rows ="";
$row ="";
$rows2 ="";
$row2 ="";



//getting details of incidents
$sql = "SELECT People_name, Vehicle_type, Incident_ID, Incident_Date, Incident_Report, Offence_ID FROM People, Vehicle, Incident WHERE Incident.People_ID = People.People_ID and Incident.Vehicle_ID = Vehicle.Vehicle_ID ORDER BY Incident_Date DESC";


// if success save results array in $rows		
if(mysqli_query($conn, $sql)){
		     		$result = mysqli_query($conn,$sql);
		     		//$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		     		//$rows = $result->fetch_assoc();
		     		//$rows = mysqli_fetch_assoc($result);
  	 				print_r($array);
					

					 $array = array();
					 while($rows = $result->fetch_assoc()){

						$array[] = $rows;
					 }
					 //print_r($array);
  							  
					// free the $result from memory (good practise)
					mysqli_free_result($result);

 }else {
		     	 	
               echo "query error: ". mysqli_error($conn);
          }


		     	 
?>

<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
<h4 class="center grey-text">Incidents</h4>
<div class="container">

	
<div class="container">
	<div class="row">
	
				
		      <?php foreach($array as $row): $id = $row['Incident_ID']; ?>
					<?php $id = $row['Incident_ID']; ?>
					<div class="card z-depth-0">
					<div class="card-content center">
					
						<h6 align="left" >Person involved: <p style="color: blue;"><?php echo htmlspecialchars($row['People_name']); ?></p></h6>
						<h6 align="left">vehicle involved:<p style="color: blue;"><?php echo $row['Vehicle_type']; ?></p></h6>
						<h6 align="left">Incident_Date: <p style="color: blue;"><?php echo htmlspecialchars($row['Incident_Date']); ?></p></h6>
						<h6 align="left">The Incident_Reportd: <p style="color: blue;" ><?php echo $row['Incident_Report']; ?></p></h6>
						<h6 align="left">Offence Type: <p style="color: blue;"><?php 
											$offen_ID = $row['Offence_ID']; 
											//echo $offen_ID;

											$get_offen_sql = "SELECT Offence_description FROM Offence WHERE Offence_ID ='$offen_ID' ";


											if(mysqli_query($conn,$get_offen_sql)){

												$result2 = mysqli_query($conn,$get_offen_sql);
		     		                           // $rows2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);
		     		                            //$rows2 = $result2->fetch_assoc();
		     		                           // $rows2s = mysqli_fetch_assoc($result2);

		     		                            $array2 = array();
					 							while($rows2 = $result2->fetch_assoc()){

												$array2[] = $rows2;
											    }
													 //print_r($array2);

		     		                            echo $array2[0]['Offence_description'];

											} else{

													 echo "query error: ". mysqli_error($conn);
											}

							 ?>
						</p></h6>

						
						<br>

					  <a href='edit_report.php?id=<?php echo  $id; ?> '  class='btn brand'>Edit Report</a>
					
					</div>
					
			      	   </div>
	     	<?php endforeach; ?>
	
	
    </div>
</div>
</div>


<?php include('templates/footer.php'); ?>

</html>

