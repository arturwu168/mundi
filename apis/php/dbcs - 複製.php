<?php
    require_once('lib.php');

    try
    {
        //$a='5<br>';
        $conn = new PDO("mysql:host=localhost; port=3307", "root", "!q2w3e4R");
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->exec("set names 'utf8mb4'");
        $conn->exec("set time_zone = '+8:00'");

        $conn->exec("use isdmain");

        //print_r($conn)."<br>";

        //print_r($a);
        
        //print_r($b);

		$conn=null;

    }
    catch(PDOException $err)
    {
        trigger_error($err);
	}

    /*
    $servername = "localhost";
    $username = "root";
    $password = "!q2w3e4R";

    $sql="CREATE DATABASE myDBPDO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    try {
      $conn = new PDO("mysql:host=localhost; port=3306", "root", "!q2w3e4R");
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // use exec() because no results are returned
      $conn->exec($sql);
      echo "Database created successfully<br>";
    } catch(PDOException $e) {
      echo $sql . "<br><br>";

      //$pErr1=json_encode($e[""]);

      echo var_dump($e);
    }

    $conn = null;	
    */
?>