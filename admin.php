<?php
session_start();

//include('login/login.php');
include('config/db_connect.php');


// check if admin is logged in
 if(!isset($_SESSION['userId2']))
{
    // not logged in
    header('Location: index.php');
    exit();
}


?>

<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
<h4 class="center grey-text"> DASHBOARD</h4>
<nav class=" white z-depth-0">
 			<div class="container">
 				    <ul id="nav-mobile" class="center hide-on-small-and-down">
 				    		<li><a href="index.php"  class="btn brand">Home Page</a></li>
 				    		<li><a href="create_police_account.php"  class="btn brand">CREATE NEW POLICE ACCOUNT</a></li>
               				<li><a href="see_incidents_to_add_fines.php"  class="btn brand">SEE INCIDENTS TO ADD FINE</a></li>
 				    </ul>
 			</div>
</nav>

<?php include('templates/footer.php'); ?>

</html>


