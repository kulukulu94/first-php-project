<?php


session_start();

//database connection
include('config/db_connect.php');

//inislizing varibales 
$rows ="";
$row ="";

// check if a user is logged in 
 if(!isset($_SESSION['userId']) and !isset($_SESSION['userId2']) )
{
    // if not logged in
    header('Location: login/login.php');
    exit();
}

// inisilizing errors array
$errors = array('search' => '');


// if submit button is clicked		
if(isset($_GET['submit']) && !empty($_GET['search'])){

		            // search input check 
					if(empty($_GET['search'])){
				      $errors['search'] = 'search somthing!';
				     } 
				     else{
							$Searching = $_GET['search'];
							if(!preg_match('/\A[a-z0-9\s]+\Z/i', $Searching)){
								$errors['search'] = 'invalied search';
							} else {
				                         // if search input is valid

									    $search = mysqli_real_escape_string($conn, $Searching);

									    // search sql statement
									    $sql = "SELECT * FROM People WHERE People_name LIKE '%$search%' OR People_licence LIKE '%$search%' ";
									
							            // query check
								     	if(mysqli_query($conn, $sql)){
						                    // to store the result of the query
								     		$result = mysqli_query($conn,$sql);
								     		// to save the result of the query as array
								     		
						  	 				$array = array();
											while($rows = $result->fetch_assoc()){

												$array[] = $rows;
											 }
											// free the $result from memory (good practise)
											mysqli_free_result($result);

						                    // closing sql connection
											mysqli_close($conn);
										
						                    // if no rows means no people were found
											if(count($array)==0){
												$errors['search'] = 'No such Person in the system!';
											}

								     	}else {
								     	 	echo "query error". mysqli_error($conn);
						     	 	
				             
				                         }
				                    }


					    }


}

		     	 
?>



<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
<h4 class="center grey-text">SEARCH FOR PEOPLE</h4>
<h6 class="center grey-text">YOU CAN LOOK UP PEOPLE BY THEIR NAMES OR DRIVING LICENCE!</h6>
<div class="container">

<form class="white" action="search_people.php" method="GET">
	<input type="text" placeholder="Search.." name="search" required>
  
	<input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
</form>

<br>


<?php if(isset($_GET['submit']) && !empty($_GET['search'])): ?>
<div >
	
	<div class="container">
		<div class="row">
			    <!-- so that it does not dispaly this text if not people were found! -->
				<?php if(count($array)!=0 )
				echo '<h4 class="center grey-text"> List of People!</h4>';
				?>
                <!-- a loop to retrieve people details from array -->
				<?php foreach($array as $row): ?>
					
					<div class="col s6 md3">
						<div class="card z-depth-0">
							
							<div align="left" class="card-content center">
								<h6 align="left" style="font-weight:bold"> <?php echo htmlspecialchars($row['People_name']); ?></h6>
								<h6 align="left">Lives in:</h6><p align="left" style="font-weight:bold"> <?php echo $row['People_address']; ?></p>
					<h6 align="left">Holds licence: </h6><p align="left" style="font-weight:bold"><?php echo $row['People_licence']; ?></p>
							</div>
							
						</div>
					</div>
				<?php endforeach; ?>	<div class=" center red-text"><?php echo $errors['search']; ?></div>
		</div>

	</div>
</div>

<?php else: ?>

<?php endif; ?>


<?php include('templates/footer.php'); ?>

</html>

