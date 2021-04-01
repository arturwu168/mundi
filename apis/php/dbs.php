<?php
	class DbServer {
		public function connect($vAry1)
		{
			if (gettype($vAry1)=='array'){
				$pJson1 =(object)$vAry1;

				if (!empty($pJson1)){
					try{
						$pConn1 = new PDO('mysql:host='.$pJson1->host.'; port='.$pJson1->port.'', $pJson1->user, $pJson1->password);
						$pConn1->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
						$pConn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$pConn1->exec('set names \''.$pJson1->charset.'\'');
						$pConn1->exec('set time_zone = \''.$pJson1->time_zone.'\'');
						//維持連線 , array(PDO::ATTR_PERSISTENT => true)

						return $pConn1;
					}catch (PDOException $e){
						trigger_error($err);
						return(false);
					}
				}else{
					return(false);
				}
				return(false);
			}else{
				return(false);
			}
		}
	}

	$gDbc1 = new DbServer;

	$gDbs=[
		[
			'serverName'=>'isd',
			'host'=>'localhost',
			'port'=>'3307',
			'user'=>'root',
			'password'=>'!q2w3e4R',
			'charset'=>'utf8mb4',
			'time_zone'=>'+8:00',
			'dbs'=>[
				'isdmain'
			]
		]
	];

	$gDb=[];

	for ($i=0; $i<count($gDbs); $i++){
		for ($j=0; $j<count($gDbs[$i]["dbs"]); $j++){
			$pStr1=$gDbs[$i]["serverName"].'_'.$gDbs[$i]["dbs"][$j];

			$gDb[$pStr1]=$gDbc1->connect($gDbs[$i]);

			$gDb[$pStr1]->exec('use '.$gDbs[$i]["dbs"][$j]);
		}	
	}

	$gDbc1 = null;
?>