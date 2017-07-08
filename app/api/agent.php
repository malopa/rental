<?php


// retrueve agent data

  $app->get('/api/agent',function($request,$responce){
    require_once('Db_connect.php');
    $db=new DbConnect();
    $conn=$db->connect();

    $responses['agents'] = array();
    $query = "SELECT username,email,phone_number,place FROM agents";
    $result = $conn->query($query);

    $data = array();
    while($row=$result->fetch_assoc()){
      $data['username'] = $row['username'];
      $data['email'] = $row['email'];
      $data['phone_number'] = $row['phone_number'];
      $data['place'] = $row['place'];
      array_push($responses['agents'],$data);
    }

    if(isset($data)){
      if(!empty($responses)){
      header('Content-Type:application/json');
    echo json_encode($responses);
  }else {
    echo 'no data in there';
  }
  }else {
    echo "oop!   no data in there";
  }
});


$app->post('/api/agent',function($request){

  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses=array();
  $username = $request->getParsedBody()['username'];
  $place = $request->getParsedBody()['place'];
  $email = $request->getParsedBody()['email'];
  $phone_number =$request->getParsedBody()['phone_number'];
  $password = $request->getParsedBody()['password'];


  $query = "INSERT INTO agents(username,email,phone_number,password,place) VALUES(?,?,?,?,?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssss",$username,$email,$phone_number,$password,$place);
  if ($stmt->execute()) {
    $responses['error']=false;
    $responses['message']="Successfully registered";

    $stmt1 = $conn->prepare("SELECT id, username, email, phone_number FROM agents WHERE email = ?");
     $stmt1->bind_param("s",$email);
     $stmt1->execute();
     $stmt1->bind_result($id, $username, $email, $phone_number);
     $stmt1->fetch();
     $user = array();
     $responses['user']['id'] = $id;
     $responses['user']['username'] = $username;
     $responses['user']['email'] = $email;
     $responses['user']['phone_number'] = $phone_number;

  }else {
    $responses['error']= mysqli_error($conn);
  }

  echo json_encode($responses);
 });



$app->post("/api/agent/logins",function($request){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses=array();
  $username = $request->getParsedBody()["email"];
  $password = $request->getParsedBody()["password"];

  $responses = array();
  $query = "SELECT  id,username FROM agents WHERE email=? AND password=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss",$username,$password);
  $stmt->execute();
  $stmt->store_result();
  $num = $stmt->num_rows;

  $stmt->bind_result($id,$username);
  if ($stmt->num_rows > 0) {
    $responses['error']=false;
    while ($stmt->fetch()) {
      $address = array();
      $responses['user']['id']=$id;
      $responses['user']['username'] = $username;
    }
  }else {
    $responses['error']=true;
    $responses['message'] = "Wrong username or password";
  }


  echo json_encode($responses);

});
// update agent information
 // $app>post('/agent/')
 $app->put('/api/agent/{id}',function($request){

   require_once('dbconnect.php');

   $id=$request->getAttribute('id');
   $email = $request->getParsedBody()['email'];


   $query = "update agent set email=? where id=?";
   $stmt = $conn->prepare($query);
   $stmt->bind_param('ss',$email,$id);
   $stmt->execute();
  });

// delete agent in the system
  // $app>post('/agent/')
  $app->delete('/api/agent/{id}',function($request){

    require_once('dbconnect.php');
    $id=$request->getAttribute('id');
    $query = "delete from agent where id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s',$id);
    $stmt->execute();
   });


$app->get('/api/agents/profile/{id}',function($request){
  require_once('Db_connect.php');
  $db=new DbConnect();
  $conn=$db->connect();

  $responses = array();

  $id = $request->getAttribute('id');
  // $id=4;
  //
  $query = "SELECT username,email,phone_number,place FROM agents WHERE id=?";
  $stmt=$conn->prepare($query);
  $stmt->bind_param("i",$id);
  $stmt->bind_result($name,$email,$phone_number,$place);
  if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $responses['name'] = $name;
        $responses['email'] = $email;
        $responses['place'] = $place;
        $responses['phone_number'] = $phone_number;
    }
  }
  // $responses['id'] = $id;
  echo json_encode($responses);
});
