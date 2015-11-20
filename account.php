<?php
include_once "checkSession.php";
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>User Account</title>

        <!-- JavaScript -->
		<script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<script src="js/classie.js"></script>
		<script type="text/javascript" src="js/moment.js"></script>

        <!-- CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet">
    
		<!-- Custom CSS -->
		<style>
            body {
                padding-top: 20px;
                padding-left: 10px;
                background: white;
            }
            
            #bs-example-navbar-collapse-1 ul li a:hover {
                border-bottom:2px #FFF solid;
            }
			
			.gradientBoxesWithOuterShadows { 
				border-style: outset;
				border-radius: 20px;
				-moz-border-radius: 10px;
				box-shadow: 2px 2px 2px #cccccc;
				-moz-box-shadow: 2px 2px 2px #cccccc;
			}
        </style>
		
		<script>
			$(document).ready(function(){
				$(".js-ajax-php-json").click(function(){
				var data = {
				"action": "test"
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
				type: "POST",
				dataType: "json",
				url: "response.php", //Relative or absolute path to response.php file
				data: data,
				success: function(data) {
				$(".the-return").html(
				"Favorite beverage: " + data["favorite_beverage"] + "<br />Favorite restaurant: " + data["favorite_restaurant"] + "<br />Gender: " + data["gender"] + "<br />JSON: " + data["json"]
				);
				alert("Form submitted successfully.\nReturned json: " + data["json"]);
				}
				});
				return false;
				});
			});
			
			function getDetails(value){
				$.ajax({
					url: "accountgetdetails.php",
					type: "post",
					data: "name=" + value, 
					success: function(data) {
						var json = JSON.parse(data);
						document.getElementById("RetrievedName").value = json.name;
						document.getElementById("RetrievedEmail").value = json.email;
						document.getElementById("RetrievedRole").value = json.role;
					},
					error: function(){
						alert("hey");
					}
				});
			}
		</script>
	</head>
  
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav" style="font-size:12px">
                        <li>
                            <a href="home.php"><span class="glyphicon glyphicon-th-large" style="padding:0px" aria-hidden="true"></span> Screening Result</a>
                        </li>
                        <li>
                            <a href="classificationTry1.php"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Health Classification</a>
                        </li>
						<li>
                            <a href="patientjourney.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Patient Journey</a>
                        </li>
						<li>
                            <a href="analysis.php"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Repeat Analysis</a>
                        </li>
                        <li>
                            <a href="geospatial.php"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Geospatial Intelligence</a>
                        </li>
						<?php
						if($_SESSION['role'] == "Admin"){
							echo '
							<li>
								<a href="dataprocessing.php"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Data Processing</a>
							</li>
							<li>
								<a href="account.php" style="background-color:#1AACBF;color:#FFF;border-bottom:2px #1AACBF solid"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> User Account</a>
							</li>
							';
						}?>
						<li>
                            <a href="logout.php"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
		
		</br></br>

		<div style="width:1340px">
			<div class="gradientBoxesWithOuterShadows" align="center" style="float:left;height:280px;width:800px;margin:0px 10px 0px 0px">
				<br />

				<table style="font-weight:bold">
					<tr>
						<th>
							<div style="text-align:center">Existing Users</div>
							<br />
						</th>
						<th colspan="2">
							<div style="text-align:center">User Details</div>
							<br />
						</th>
					</tr>
					<tr>
						<td rowspan="4" width="350px">
							<select id="listbox" name="listbox" class="form-control" multiple="multiple" size="10" style="width:280px" onChange="getDetails(this.value)">
								<?php
									$query = 'SELECT name FROM account';
									$result = mysqli_query($mysqli, $query);
									
									while($row = mysqli_fetch_assoc($result)){
										echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
									}
								?>
							</select>
						</td>
						<td width="50px" >Name</td>
						<td width="350px">
							<div class="col-md-12">
								<input id="RetrievedName" name="Name" type="text" class="form-control">
							</div>
						</td>
					</tr>
					<tr>
						<td>Email</td>
						<td>
							<div class="col-md-12">
								<input id="RetrievedEmail" name="Email" type="text" class="form-control">
							</div>
						</td>
					</tr>
					<tr>
						<td>Role</td>
						<td>
							<div class="col-md-8">
								<select id="RetrievedRole" name="Role" class="form-control">
								<option value="Admin">Admin</option>
								<option value="Senior Management">Senior Management</option>
								<option value="Operation">Operation</option>
							</select>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div align="center">
								<button id="Update" name="Update" class="btn btn-primary">Update</button>
								<button id="Delete" name="Delete" class="btn btn-primary">Delete</button>
							</div>
						</td>
					</tr>
				</table>
				
				<br />
			</div>
			
			<div class="gradientBoxesWithOuterShadows" align="center" style="float:right;height:280px;width:500px;margin:0px 15px 0px 10px">
				<br />

				<table style="font-weight:bold">
					<tr>
						<th colspan="2">
							<div style="text-align:center">Create New User</div>
							<br />
						</th>
					</tr>
					<tr>
						<td width="50px" style="padding:11px 0px 11px 0px">Name</td>
						<td width="350px">
							<div class="col-md-12">
								<input id="Name" name="Name" type="text" class="form-control">
							</div>
						</td>
					</tr>
					<tr>
						<td style="padding:10px 0px 10px 0px">Email</td>
						<td>
							<div class="col-md-12">
								<input id="Email" name="Email" type="text" class="form-control">
							</div>
						</td>
					</tr>
					<tr>
						<td style="padding:11px 0px 11px 0px">Role</td>
						<td>
							<div class="col-md-8">
								<select id="Role" name="Role" class="form-control">
								<option value="Admin">Admin</option>
								<option value="Senior Management">Senior Management</option>
								<option value="Operation">Operation</option>
							</select>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding:13px 0px 13px 0px">
							<div align="center">
								<button id="Create" name="Create" class="btn btn-primary">Create</button>
							</div>
						</td>
					</tr>
				</table>
				
				<br />
			</div>
		</div>
		
		<div style="clear:both"></div>
	</body>
</html>