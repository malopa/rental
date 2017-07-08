<?php
// register house
$app->post('/api/upload',function($request){

    require_once('dbconnect.php');
    header('Content-Type:multipart/form-data');
		$image = $request->getParsedBody()['image'];
    $name = $request->getParsedBody()['name'];
    // $h_id=2;

		$sql ="SELECT id FROM agent WHERE id='2'";
		$res = mysqli_query($conn,$sql);

		$id = 0;

		while($row = mysqli_fetch_array($res)){
				$id = $row['id'];
		}

		$path = "uploads/$id.png";

		$actualpath = "http://rental/app/api/$path";

		$sql = "INSERT INTO picture (address) VALUES ('$actualpath')";

		if(mysqli_query($con,$sql)){
			file_put_contents($path,base64_decode($image));
			echo "Successfully Uploaded";
		}

		mysqli_close($conn);
	}else{
		echo "Error";
	}


 ?>
