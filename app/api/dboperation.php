<?php

/**
 *
 this classs perfoms all database opertion needed by the rental house application
 */

class DbOperation
{
  private $con;

  // inside constructor assign the connection to  server to variable $con
  function __construct()
  {
    require_once('DbConnect.php');
    $db = new DbConnect();
    $this->con = $db->connect();
  }
  // get five picture of the community house ;
  function getHouseToDisplayHomePage(){
    $stmt=$this->con->prepare("SELECT picture_adress FROM house WHERE category=? LIMIT 5" );
    $stmt->bind_param("s",$category);
  }
}

 ?>
