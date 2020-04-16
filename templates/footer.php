


<style type="text/css">
	
.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: red;
  color: white;
  text-align: center;
}
</style>

<footer class="section">
	<?php 

 if(isset($_SESSION['userId']) OR isset($_SESSION['userId2']))
{
    echo '<form class="white"><input type="button" value="Go back!" class="btn brand z-depth-0" onclick="history.back()"></form>';
}
 ?>
 
	<div class="center grey-text">Copyright 2020 Traffic Police System</div>
</footer>
</body>