<?php


session_start();

error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");


//database connection
include('config/db_connect.php');

// check if a user is logged in 
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

$people = $vehicle ='';

//initilizing errors array
 $errors = array('search' => '','people' => '', 'vehicle' => '');




// check if submit button is clicked and search is not empty
if(isset($_POST['submit']) && !empty($_POST['search'])){

                                   //validating search input
									if(empty($_POST['search'])){
									$errors['search'] = 'search somthing';
								    } else{
												$Searching = $_POST['search'];
												if(!preg_match('/\A[a-z0-9\s]+\Z/i', $Searching)){
													$errors['search'] = 'invalied search format';
												}

						                  }

									   $search = mysqli_real_escape_string($conn, $Searching);

									

						                //sql statement to get vehicle's  make and colour
									    $sql = "SELECT Vehicle_type, Vehicle_colour FROM Vehicle WHERE Vehicle_licence = '$search' ";

								     	if(mysqli_query($conn, $sql)){

								     		// to store the result of the query
								     		$result = mysqli_query($conn,$sql);

								     		// to save the result of the query as array
								     		//$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

								     		$array = array();
											while($rows = $result->fetch_assoc()){

												$array[] = $rows;
											 }
						  	 
											// free the $result from memory (good practise)
											//mysqli_free_result($result);
											
											// check if there is vehicles in search results
											if(count($array)==0){
												$errors['vehicle'] = 'Vehicle is not found';
											}

								     	 }else {
								     	 	echo "query error". mysqli_error($conn);
								   
								     	 }
						      
								     	 //sql statement to get the owner's details
										$sql2 = "SELECT People_name, People_licence FROM People WHERE People_ID IN ( SELECT People_ID FROM Ownership WHERE Vehicle_ID IN ( SELECT Vehicle_ID FROM Vehicle WHERE Vehicle_licence = '$search'))";
										
										

						            	if(mysqli_query($conn, $sql2)){
						            		// to store the result of the query
								     		$result2 = mysqli_query($conn,$sql2);
								     		// to save the result of the query as array
								     		//$rows2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);

								     		$array2 = array();
											while($rows = $result2->fetch_assoc()){

												$array2[] = $rows;
											 }


						  	 	
											// free the $result2 from memory (good practise)
											//mysqli_free_result($result2);
											
											// check if there is people in search results
											if(count($array2)==0){
												$errors['people'] = 'Owner is not found';
											}

								     	 }else {
								     	 	echo "query error". mysqli_error($conn);
								   
						                    }

								     	
                                
      }

		     	 
?>

<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>

<h4 class="center grey-text">SEARCH FOR VEHICLE</h4>
<h6 class="center">ENTER THE EXACT LICENCE PLATE OF THE VEHICLE</h6>
<div class="container">

	<form class="white" action="search_vehicle.php" method="POST">
			  <input type="text" placeholder="Search.." name="search" required>
			  <div class="red-text"><?php echo $errors['search']; ?></div>
			  <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
    </form>

<br>



<?php if(isset($_POST['submit']) && !empty($_POST['search'])): ?>
		<div >
				<h4 class="center grey-text">Vehicle Details</h4>
				<!-- to display errors if the vehicle or the owner were not found -->
				<div class="center red-text"><?php echo $errors['vehicle']; ?></div>
				<div class="center red-text"><?php echo $errors['people']; ?></div>


				<div class="container">
							<div class="row">

								          <!-- loop for showing vehicle details -->
											<?php foreach($array as $row): ?>
												<div class="col s6 md3">
													<div class="card z-depth-0">
														
														<div class="card-content center">
															<h6 align="left">The Vehicle type:</h6><p align="left" style="font-weight:bold"><?php echo htmlspecialchars($row['Vehicle_type']); ?></p>
															
															<h6 align="left">The Vehicle Colour:</h6><p align="left" style="font-weight:bold"><?php echo $row['Vehicle_colour']; ?></p>
																								
														</div>
														
													</div>
												</div>
											<?php endforeach; ?>	
									

									  <!--  loop for showing owner -->
										<?php foreach($array2 as $row2): ?>
											<div class="col s6 md3">
												<div class="card z-depth-0">
													
													<div class="card-content center">

														<h6 align="left">The Vehicle Owner: </h6><p align="left" style="font-weight:bold"><?php echo htmlspecialchars($row2['People_name']); ?></p>

														<h6 align="left">hold licence: </h6><p align="left" style="font-weight:bold"><?php echo $row2['People_licence']; ?></p>
										
													</div>
													
												</div>
											</div>
										<?php endforeach; ?>	
							</div>
					</div>

		</div>
</div>
<?php else: ?>
<display an error>
<?php endif; ?>


<?php include('templates/footer.php'); ?>

</html>

