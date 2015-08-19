<?php
include("mysqli.connect.php");
session_start();
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$inputUsername = $_POST['username'];
	$inputPassword = $_POST['password'];
	
	$sql = "SELECT * FROM account WHERE username = '$inputUsername' and password = '$inputPassword'";
	
	$result = $mysqli->query($sql);
	if ($mysqli->errno){
		echo $mysqli->error;
	}
	
	$row = $result->fetch_row();
	
	if($row == null){
		$error = "Your username or password is invalid.";
	}
	else{
		$retrievedUsername = $row[0];
		$retrievedPassword = $row[1];
		$access = $row[2];
		
		if($retrievedUsername == $inputUsername && $retrievedPassword == $inputPassword){
			$_SESSION['user'] = $inputUsername;
			$_SESSION['access'] = $access;

			if($access == "operation"){
				header("location: patientjourney.php");
			}
			else{
				header("location: home.php");
			}
		}
		else{
			$error = "Your username or password is invalid.";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Login</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- Javascript -->
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <!-- CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
    </head>
    <body>
        <!--login modal-->
        <div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">KTPH Population Health Dashboard</h1>
                    </div>
                    <div class="modal-body">
                        <form class="form col-md-12 center-block" action="" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control input-lg" name="username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control input-lg" name="password" placeholder="Password">
                            </div>
							
                            <input type="hidden" name="loginMethod" value="ui" />
							
                            <div class="form-group">
                                <button class="btn btn-primary btn-lg btn-block">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
							<div style="text-align:left; color:#cc0000"><?php echo $error; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>