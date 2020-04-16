<?php // connect to DB
$conn = mysqli_connect('mysql.cs.nott.ac.uk', 'eexma89', 'XIDKRE', 'eexma89');

// check connection

if (!$conn){
	echo 'connection error: ' . mysqli_connect_error();
}
 ?>