<head>
	<title>TRAFIC POLICE SYSTEM</title>
	<!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style type="text/css">
    	.brand{
    		background: #cbb09c !important;
    	}
    	.brand-text{
    		color: #cbb09c !important;
    	}
    	form{
    		max-width: 460px;
    		margin: 20px auto;
    		padding: 20px;
    	}
     
.select-css{
    display: block;
    color: #444;
    line-height: 1.3;
    padding: .6em 1.4em .5em .8em;
    max-width: 460px;
    margin: 20px auto;
   
   }









    </style>
</head>
 	<body class ="grey lighten-4">
 		<nav class=" white z-depth-0">
 			<div class="container">
                            <?php  
                                        if(isset($_SESSION['userId2']) or isset($_SESSION['userId']))
                                        {
                                           echo '<a href="index.php" class="brand-logo brand-text">POLICE SYSTEM</a>';
                                                              
                                        }else{

                                            echo '<a href="" class="brand-logo brand-text">POLICE SYSTEM</a>';
                                        }
                                                              ?>
                     
 				    <ul id="nav-mobile" class="right hide-on-small-and-down">
 					    
                            <?php  
                                        if(isset($_SESSION['userId2']))
                                        {
                                           echo '<li><a href="admin.php" class="btn brand z-depth-10">Admin Dashboard</a></li>';
                                                              
                                        }
                                                              ?>

                          

                             <?php  
                                        if(isset($_SESSION['userId2']) or isset($_SESSION['userId']))
                                        {
                                           echo '<li><a href="change_password.php" class="btn brand z-depth-10">Change Password</a></li>';
                                                              
                                        }
                                                              ?>

                             <?php  
                                        if(isset($_SESSION['userId2']) or isset($_SESSION['userId']))
                                        {
                                           echo '<li><a href="logout.php" class="btn brand z-depth-10">Logout</a></li>';
                                                              
                                        }
                                                              ?>
                             

 				    </ul>
 			</div>
 		</nav>


