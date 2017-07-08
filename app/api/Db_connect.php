<?php

  class DbConnect
  {
    private $conn;

    function __construct()
    {
      include('configdata.php');
    }

    function connect(){
      $this->conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
      if(!$this->conn)
      {
        return mysqli_errno();
      }
      return $this->conn;
    }


  }

// $db = new DbConnect();
//
// $db->connect());

 ?>
