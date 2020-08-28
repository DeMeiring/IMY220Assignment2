<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="DeVilliers Meiring">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)) {
                    echo "<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

                    echo "<form action='login.php' method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='hidden' name='loginEmail' value =" . $_POST["loginEmail"] . " />
									<input type='hidden' name='loginPass' value =" . $_POST["loginPass"] . " />
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
                    if(isset($_POST["submit"])){
                        $uID = $row["user_id"];
                        $target_dir = "gallery/";
                        $fileToUpload = $_FILES["picToUpload"];
                        if (($fileToUpload["type"] == "image/jpg" || $fileToUpload["type"] == "image/jpeg") && $fileToUpload["size"] < 1048576) {
                            $query1 = "INSERT INTO tbgallery (image_id,user_id,filename) VALUES (null," . $uID . ",'" . $fileToUpload["name"] . "')";
                            $r = mysqli_query($mysqli, $query1);
                            move_uploaded_file($fileToUpload["tmp_name"], $target_dir . $fileToUpload["name"]);
                            $imageQuery = "Select * from tbgallery Where user_id=".$uID;
                            $imageRes = mysqli_query($mysqli,$imageQuery);
                            if($imageRes->num_rows>0){
                                echo "<div class='container'>";
                                echo "<div class='row imageGallery'>";
                                while($imageResRow = $imageRes->fetch_assoc()){
                                    echo "
                                             <div class='col-3' style='background-image: url(gallery/"
                                            .$imageResRow["filename"].
                                            "'.jpg)'>
                                            </div>
                                    ";
                                }
                                echo "</div>";
                                echo "</div>";
                            }

                        }
                    }
                }











				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>