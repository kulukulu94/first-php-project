<?php
session_start();

//include('login/login.php');
include('config/db_connect.php');

 if(!isset($_SESSION['userId']) and !isset($_SESSION['userId2']) )
{
    // not logged in
    header('Location: login/login.php');
    exit();
}


$rows ="";
$row ="";
$rows2 ="";
$row2 ="";

//initilizing variables
$sql = "SELECT People_name, Vehicle_type, Incident_ID, Incident_Date, Incident_Report, Offence_ID FROM People, Vehicle, Incident WHERE Incident.People_ID = People.People_ID and Incident.Vehicle_ID = Vehicle.Vehicle_ID ORDER BY Incident_Date DESC";

//getting details of incidents
if(mysqli_query($conn, $sql)){
		     		$result = mysqli_query($conn,$sql);
		     		//$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  	 				

  	 				$array = array();
					 while($rows = $result->fetch_assoc()){

						$array[] = $rows;
					 }
					// free the $result from memory (good practise)
					mysqli_free_result($result);
					

			     	 }else {
			     	 	
	               			echo "query error:". mysqli_error($conn);
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
		     		                            //$rows2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);

		     		                            $array2 = array();
												 while($rows = $result2->fetch_assoc()){

													$array2[] = $rows;
												 }

		     		                            echo $array2[0]['Offence_description'];

											} else{

													 echo "query error: ". mysqli_error($conn);
											}

							 ?>
						</p></h6>

											<br>


											 
										  <a href='add_fine.php?id=<?php echo  $id; ?> '  class='btn brand'>ADD FINE</a>
											
										
								      	  </div>
						     	<?php endforeach; ?>
			
	    					</div>
	</div>
</div>


<?php include('templates/footer.php'); ?>

</html>

