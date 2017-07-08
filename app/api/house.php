<?php

$app->get('/api/house/apartment',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['image_address'] = array();
  $query = "SELECT  id,picture_address FROM house WHERE house_category='apartment' LIMIT 5";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($id,$picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['id']=$id;
    $address['pic'] = $picture_address;

    array_push($responses['image_address'],$address);
  };

  echo json_encode($responses);

});

// getMarket house
$app->get('/api/house/market',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['makert_picture'] = array();
  $query = "SELECT  picture_address FROM house WHERE house_category='market' LIMIT 5";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['pic'] = $picture_address;

    array_push($responses['makert_picture'],$address);
  };

  echo json_encode($responses);

});

// getMarket house
$app->get('/api/house/master',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['master_picture'] = array();
  $query = "SELECT  picture_address FROM house WHERE house_category='master' LIMIT 5";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['pic'] = $picture_address;

    array_push($responses['master_picture'],$address);
  };

  echo json_encode($responses);

});


// getFurnished house profile
$app->get('/api/house/furnished',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['furnished_picture'] = array();
  $query = "SELECT  picture_address FROM house WHERE house_category='furnished' LIMIT 5";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['pic'] = $picture_address;

    array_push($responses['furnished_picture'],$address);
  };

  echo json_encode($responses);

});

// register house
$app->post('/api/house',function($request,$response){
  header("Content-Type:application/x-www-form-urlencoded");
  // require_once('config.php');
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses=array();

  $street = $request->getParsedBody()['street_name'];
  $description = $request->getParsedBody()['description'];
  $price = $request->getParsedBody()['price'];
  $category = $request->getParsedBody()['category'];
  $bath = (int)$request->getParsedBody()["bath"];
  $room = (int)$request->getParsedBody()["room"];
  $agent_id = (int)$request->getParsedBody()["agent_id"];

  $query = "INSERT INTO house(bath,room,price,house_category,agent_id,description) VALUES(?,?,?,?,?,?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("iissis",$bath,$room,$price,$category,$agent_id,$description);
  if($stmt->execute())
  {
    $id=$stmt->insert_id;
    $responses['message'] = "uploaded Successfully";
    $responses['success'] = $id;
    // array_push($responses['success'],$id,);
    echo json_encode($responses);
  }
 });

 $app->post('/api/upload/profile',function($request,$response){
  //  require_once('config.php');
   require_once('Db_connect.php');
   $db=new DbConnect();
   $conn=$db->connect();

   $responses=array();
   $house_id = $request->getParsedBody()['id'];
  // $street = $request->getParsedBody()['street'];
   $target_dir="../4to/";
   $image_path = $target_dir.basename($_FILES['profile']['name']);
   $full_path = "http://192.168.137.1/rental/4to/".basename($_FILES['profile']['name']);
   if (move_uploaded_file($_FILES['profile']['tmp_name'],$image_path)) {
      $street = $full_path;
      // $house_id=81;
      // $query = "INSERT INTO street(street_name,house_id) VALUES(?,?)";
      $query = "UPDATE house SET picture_address = ? WHERE id=?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ss",$street,$house_id);
      if($stmt->execute())
      {
        $responses['message']="uploaded Successfully";
        echo json_encode($responses);
      }else {
        $responses['message']="no data ".mysqli_error($conn);
        echo json_encode($responses);
      }
   }

 });

 $app->post('/api/house/gallery',function($request,$response){

   require_once('Db_connect.php');
   $db=new DbConnect();
   $conn=$db->connect();

   $target_dir="../4to/";
   $responses=array();
   $imageAdded['picture'] = array();

   $house_id = (int)$request->getParsedBody()['id'];



   //start static file upload
   $file_path1=$target_dir.basename($_FILES['photo1']['name']);
   $file_path2=$target_dir.basename($_FILES['photo2']['name']);
   $file_path3=$target_dir.basename($_FILES['photo3']['name']);
   $file_path4=$target_dir.basename($_FILES['photo4']['name']);
   $file_path5=$target_dir.basename($_FILES['photo5']['name']);

   $imagePath1 = "http://192.168.137.1/rental/4to/".basename($_FILES['photo1']['name']);
   $imagePath2 = "http://192.168.137.1/rental/4to/".basename($_FILES['photo2']['name']);
   $imagePath3 = "http://192.168.137.1/rental/4to/".basename($_FILES['photo3']['name']);
   $imagePath4 = "http://192.168.137.1/rental/4to/".basename($_FILES['photo4']['name']);
   $imagePath5 = "http://192.168.137.1/rental/4to/".basename($_FILES['photo5']['name']);

       if(
       move_uploaded_file($_FILES['photo1']['tmp_name'],$file_path1)&&
       move_uploaded_file($_FILES['photo2']['tmp_name'],$file_path2)&&
       move_uploaded_file($_FILES['photo3']['tmp_name'],$file_path3)&&
       move_uploaded_file($_FILES['photo4']['tmp_name'],$file_path4)&&
       move_uploaded_file($_FILES['photo5']['tmp_name'],$file_path5)
       )
       {
         array_push($imageAdded['picture'],$imagePath1,$imagePath2,$imagePath3,$imagePath4,$imagePath5);


// start here
         $count=0;
         for($i=0; $i<5; ++$i){
           $query = "INSERT INTO image_gallery(image_address,house_id) VALUES(?,?)";
           $stmt = $conn->prepare($query);
           $stmt->bind_param("ss",$imageAdded['picture'][$i],$house_id);
           if ($stmt->execute()) {
             $count++;
           }
         }
         if($count == 5)
         {
           $responses['message']="uploaded Successfully";
           $house_id =(int)$request->getAttribute('house_id');
           $querydisplayUploadedImage = "SELECT image_address FROM image_gallery WHERE house_id=?";
           $stmt2 = $conn->prepare($querydisplayUploadedImage);
           $stmt2->bind_param("i",$house_id);
           $data['image'] = array();
           if($stmt2->execute()){
         	  $m = $stmt2->get_result();
         	while($re = $m->fetch_assoc()){
         		array_push($imageAdded['picture'], $re['image_address']);
         	}
           echo json_encode($imageAdded,JSON_PRETTY_PRINT);
         }
             echo json_encode($imageAdded);
           }


        }//else {
          //  $responses['message']="no data ".mysqli_error($conn);
          echo json_encode($responses);
          // }
        //  echo json_encode($responses);
      //  echo json_encode($response);
 });


// get appartment community house
$app->get('/api/apartment/community',function($request,$response){
  require_once('dbconnect.php');
  // $name= $request->getAttribute('type');
  $query = "SELECT * FROM house WHERE type='apartment'";
  $result = $conn->query($query);
  while($row=$result->fetch_assoc()){
    $data[] = $row;
  }

  if(isset($data)){
    if(!empty($data)){
    header('Content-Type:application/json');
  echo json_encode($data);
}else {
  echo 'no data in there';
}
}else {
  echo "oop!   no data in there";
}
});

// get full house rental community house
$app->get('/api/fullhouse',function($request,$responce){
  require_once('dbconnect.php');
  $query = "SELECT * FROM house WHERE type='fullhouse'";
  $result = $conn->query($query);
  while($row=$result->fetch_assoc()){
    $data[] = $row;
  }

  if(isset($data)){
    if(!empty($data)){
    header('Content-Type:application/json');
  echo json_encode($data);
}else {
  echo 'no data in there';
}
}else {
  echo "oop!   no data in there";
}
});

// sales house
// get appartment community house
$app->get('/api/salehouse',function($request,$responce){
  require_once('dbconnect.php');
  $query = "SELECT * FROM house WHERE type='sale'";
  $result = $conn->query($query);
  while($row=$result->fetch_assoc()){
    $data[] = $row;
  }

  if(isset($data)){
    if(!empty($data)){
    header('Content-Type:application/json');
  echo json_encode($data);
}else {
  echo 'no data in there';
}
}else {
  echo "oop!   no data in there";
}
});

// get appartment community house
$app->get('/api/furnishedhouse',function($request,$responce){
  require_once('dbconnect.php');
  $query = "SELECT * FROM house WHERE type='furnished'";
  $result = $conn->query($query);
  while($row=$result->fetch_assoc()){
    $data[] = $row;
  }

  if(isset($data)){
    if(!empty($data)){
    header('Content-Type:application/json');
  echo json_encode($data);
}else {
  echo 'no data in there';
}
}else {
  echo "oop!   no data in there";
}
});

$app->get('/api/house/gallery/{house_id}',function($request,$response){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $imageAdded['picture'] = array();
  $house_id =(int)$request->getAttribute('house_id');
  $querydisplayUploadedImage = "SELECT image_address FROM image_gallery WHERE house_id=?";
  $stmt2 = $conn->prepare($querydisplayUploadedImage);
  $stmt2->bind_param("i",$house_id);
  $data['image'] = array();
  if($stmt2->execute()){
	  $m = $stmt2->get_result();
	while($re = $m->fetch_assoc()){
		array_push($imageAdded['picture'], $re['image_address']);
	}
  echo json_encode($imageAdded,JSON_PRETTY_PRINT);
}

});

$app->get('/api/house/apartment/all',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['apartment_image_address'] = array();
  $query = "SELECT  id,price,picture_address FROM house WHERE house_category='apartment'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($id,$price,$picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['id'] = $id;
    $address['price'] = $price;
    $address['picture_address'] = $picture_address;

    array_push($responses['apartment_image_address'],$address);
  };

  echo json_encode($responses);
});


$app->get('/api/house/market/all',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['market_image_address'] = array();
  $query = "SELECT  id,price,picture_address FROM house WHERE house_category='furnished'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($id,$price,$picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['id'] = $id;
    $address['price'] = $price;
    $address['picture_address'] = $picture_address;

    array_push($responses['market_image_address'],$address);
  };

  echo json_encode($responses);
});


$app->get('/api/house/master/all',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['master_image_address'] = array();
  $query = "SELECT  id,price,picture_address FROM house WHERE house_category='master'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($id,$price,$picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['id'] = $id;
    $address['price'] = $price;
    $address['picture_address'] = $picture_address;

    array_push($responses['master_image_address'],$address);
  };

  echo json_encode($responses);
});

$app->get('/api/house/furnished/all',function($request,$responce){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses['furnished_image_address'] = array();
  $query = "SELECT  id,price,picture_address FROM house WHERE house_category='market'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($id,$price,$picture_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['id'] = $id;
    $address['price'] = $price;
    $address['picture_address'] = $picture_address;

    array_push($responses['furnished_image_address'],$address);
  };

  echo json_encode($responses);
});


$app->get("/api/house/picture/{id}",function($request,$response){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();
  $id = $request->getAttribute('id');
  $responses['image_image'] = array();
  $query = "SELECT  image_address FROM image_gallery WHERE house_id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i",$id);
  $stmt->execute();
  $stmt->bind_result($image_address);
  while ($stmt->fetch()) {
    $address = array();
    $address['image'] = $image_address;
    array_push($responses['image_image'],$address);
  };
  echo json_encode($responses);

});





$app->get("/api/house/data/{id}",function($request,$response){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses = array();
  $id = $request->getAttribute('id');
  $query = "SELECT  bath,room,price,description FROM house WHERE id=?";

  $stmt = $conn->prepare($query);
  $stmt->bind_param("i",$id);
  $stmt->execute();
  $stmt->bind_result($bath,$room,$price,$description);
  while ($stmt->fetch()) {
    $address = array();
    $responses['data_address']['bath'] = $bath;
    $responses['data_address']['room'] = $room;
    $responses['data_address']['price'] = $price;
    $responses['data_address']['description'] = $description;
    // array_push($responses,$address);
  };
  echo json_encode($responses);

});
