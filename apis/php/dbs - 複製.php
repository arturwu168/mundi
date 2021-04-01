<?php
	class DbServer1 {
		static $host = 'localhost';
		static $port = '3306';
		static $charset = 'utf8mb4';
		static $user = 'root';
		static $password = '&ujm8ik,9ol>';
		static $dbname = 'isdmain';

		public function connect($vDbname=null)
		{
			try{
				$pObjConn=new PDO('mysql:host='.DbServer1::$host.'; '.DbServer1::$port.'; charset='.DbServer1::$charset.'; dbname='.DbServer1::$dbname.'; ', DbServer1::$user, DbServer1::$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".DbServer1::$charset));
				$pObjConn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
				$pObjConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pObjConn->exec("set names '".DbServer1::$charset."'");
                $pObjConn->exec("set time_zone = '+8:00'");
				return $pObjConn;
				//維持連線 , array(PDO::ATTR_PERSISTENT => true)
			}catch (PDOException $e){
				return "Error:".$e->getMessage()."<br />File:".$e->getFile();
			}
		}
	}

	class dbRetJson {
		public $err=0;
		public $datas=[];
		public $tRecord=0;
		public $descs=[];
	}

	$gDbs1 = new DbServer1;

	


?>