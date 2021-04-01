<?php
    
	ini_set('date.timezone','Asia/Taipei');

    error_reporting(E_ALL);
	ini_set('display_errors', 0);
	ini_set('log_errors', 0);
	ini_set('error_log', __DIR__ . '/errorlog/'.date("Y-m-d").'.log');

	class retJson {
		public $err=0;
		public $records=0;
		public $datas=[];
		public $descs=[];
	}

	if (!session_status()){
		session_start();
	}

	function guid($vStr1=''){
		if ((float)phpversion()>=7.1){
			$uuid = mb_strtoupper(md5(session_create_id($vStr1)), 'UTF-8');
		}else{
			$uuid = mb_strtoupper(md5($vStr1.str_replace('.', '', uniqid(mt_rand(), true))), 'UTF-8');
		}
		
		return $uuid;
	}

	function ff2dbf($path, $mpath, &$arr, $vDbs1, $vTrees) {
		global $pfnum1, $pfnum2;
		$pDbs1=$vDbs1;
		$dir_handle = opendir($path);
		$pVal1=0;

		while (($file = readdir($dir_handle)) !== false) {
 			//$p = realpath($path . '/' . $file);
			$p = $path . '/' . $file;

			if ($file != "." && $file != ".." && $file != ".vs" && $file != "frameworks" && $file != "frame2db.php" && substr($file, 0, 1) != "_") {

				$arr[] = $p;
				$pVal1=fGuid($pVal1);

				if (is_dir($p)) {
					$pfnum2++;
					$pFolder1s= explode("/", str_replace($mpath.'/','',$p));
					$pSpace=str_repeat("&nbsp", ((count($pFolder1s)-1)*4));
					$player1=(count($pFolder1s)-1);
					$player2=(count($pFolder1s));
					$pfcode1=str_replace('.','_', $file).'_'.$player2;
					$ppath1=str_replace($mpath,'',$path);

					//echo $path."<br>";
					//echo $ppath1."<br>";
					//exit;

					// 找出上一層資料夾
					$pPpath1="";
					if ((count($pFolder1s)-1)===0){
						$pFolder2s=explode("/", $p);
						$pPpath1=$pFolder2s[(count($pFolder2s)-2)];
					}else{
						$pPpath1=$pFolder1s[(count($pFolder1s)-2)];
					}
					$pPpath1=str_replace('.','_', $pPpath1).'_'.$player1;

					// 找出上一層資料夾
					if (isset($vTrees[$pPpath1])){
						if (!isset($vTrees[$pfcode1])){
							$vTrees[$pfcode1]=[
								"fcode1_st" => $vTrees[$pPpath1]["fcode1_st"]
								, "psid1_nu" => $vTrees[$pPpath1]["sid_nu"]
								, "sid_nu" => $pVal1
							];

							// 加入資料庫
							$pSql1 = 'insert into a1_framework(';
							$pSql1.= '  sid_nu';
							$pSql1.= ', psid1_nu';
							$pSql1.= ', fcode1_st';
							$pSql1.= ', code1_st';
							$pSql1.= ', name1_st';
							$pSql1.= ', path1_st';
							$pSql1.= ', property1_nu';
							$pSql1.= ') values(?, ?, ?, ?, ?, ?, ?)';

							$pSth1 = $pDbs1->prepare($pSql1);
							$pSth1->execute([
								 $pVal1
								,$vTrees[$pPpath1]["sid_nu"]
								,$vTrees[$pPpath1]["fcode1_st"]
								,$pfcode1
								,$file
								,$ppath1
								,2
							]);
							// 加入資料庫
						}
						//echo $vTrees[$pPpath1]["sid_nu"].'-->';
						//echo $pSpace.$file.'['.$pVal1.']('.$pPpath1.':'.$vTrees[$pPpath1]["sid_nu"].')';
					}else{
						echo $pPpath1.'path:'.$vTrees[$pPpath1].'<br />';
					}
					
					//if ($player2==2){
					//	exit;
					//}
					
					//echo "<br>";

					ff2dbf($p, $mpath, $arr, $vDbs1,  $vTrees);
				}else{
					$pfnum1++;
					$pFsize = filesize($p);
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$pFtype1=finfo_file($finfo, $p);
					finfo_close($finfo);

					$pIsText1=strpos($pFtype1, 'text');

					if (gettype($pIsText1)==="boolean"){
						if ($pIsText1){
							$pIsText1=0;
						}else{
							$pIsText1=-1;
						}
					}

					$pFolder1s= explode("/", str_replace($mpath.'/','',$p));
					$pSpace=str_repeat("&nbsp", (count($pFolder1s)-1)*4);
					$player1=(count($pFolder1s)-1);
					$player2=(count($pFolder1s));
					$pfcode1=str_replace('.','_', $file).'_'.$player2;
					$ppath1=str_replace($mpath,'',$path);

					// 找出上一層資料夾
					$pPpath1="";
					if ((count($pFolder1s)-1)===0){
						$pFolder2s=explode("/", $p);
						$pPpath1=$pFolder2s[(count($pFolder2s)-2)];
					}else{
						$pPpath1=$pFolder1s[(count($pFolder1s)-2)];
					}
					$pPpath1=str_replace('.','_', $pPpath1).'_'.$player1;

					//echo "parent path:".$pPpath1;
					//echo "<br />";
					//echo "code:".$pfcode1;
					//echo "<br />";
					/*echo $ppath1;
					echo "<br />";
					echo $file;
					echo "<br />";
					exit;*/
					// 找出上一層資料夾

					if ($pIsText1>=0){
						if (isset($vTrees[$pPpath1])){
							if (!isset($vTrees[$pfcode1])){
								$vTrees[$pfcode1]=[
									"fcode1_st" => $vTrees[$pPpath1]["fcode1_st"]
									, "psid1_nu" => $vTrees[$pPpath1]["sid_nu"]
									, "sid_nu" => $pVal1
								];

								// 加入資料庫
								$pSql1 = 'insert into a1_framework(';
								$pSql1.= '  sid_nu';
								$pSql1.= ', psid1_nu';
								$pSql1.= ', fcode1_st';
								$pSql1.= ', code1_st';
								$pSql1.= ', name1_st';
								$pSql1.= ', path1_st';
								$pSql1.= ', program1_tx';
								$pSql1.= ', size1_nu';
								$pSql1.= ', property1_nu';
								$pSql1.= ') values(?, ?, ?, ?, ?, ?, ?, ?, ?)';

								$pContent1=file_get_contents($p);
								$pContent1=mb_convert_encoding($pContent1, 'UTF-8', mb_detect_encoding($pContent1, 'UTF-8, ISO-8859-1', true));

								$pSth1 = $pDbs1->prepare($pSql1);
								$pSth1->execute([
									 $pVal1
									,$vTrees[$pPpath1]["sid_nu"]
									,$vTrees[$pPpath1]["fcode1_st"]
									,$pfcode1
									,$file
									,$ppath1
									,$pContent1
									,$pFsize
									,3
								]);
								// 加入資料庫
							}

							//echo $pSpace.$file.'('.$pFsize.')'.'['.$pVal1.']('.$vTrees[$pPpath1]["sid_nu"].')['.$pFtype1.']';
							//echo "<br />".$pSpace."path:".$p;
							//echo "<br />".$pSpace."size:".$pFsize;
							//echo "<br />";
							//echo file_get_contents($p);
							//exit;
						}else{
							echo $pPpath1.'path:'.$vTrees[$pPpath1].'<br />';
						}
					}else{
						if (isset($vTrees[$pPpath1])){
							if (!isset($vTrees[$pfcode1])){
								$vTrees[$pfcode1]=[
									"fcode1_st" => $vTrees[$pPpath1]["fcode1_st"]
									, "psid1_nu" => $vTrees[$pPpath1]["sid_nu"]
									, "sid_nu" => $pVal1
								];

								// 加入資料庫
								$pSql1 = 'insert into a1_framework(';
								$pSql1.= '  sid_nu';
								$pSql1.= ', psid1_nu';
								$pSql1.= ', fcode1_st';
								$pSql1.= ', code1_st';
								$pSql1.= ', name1_st';
								$pSql1.= ', path1_st';
								$pSql1.= ', program1_tx';
								$pSql1.= ', size1_nu';
								$pSql1.= ', property1_nu';
								$pSql1.= ') values(?, ?, ?, ?, ?, ?, ?, ?, ?)';

								$pSth1 = $pDbs1->prepare($pSql1);
								$pSth1->execute([
									 $pVal1
									,$vTrees[$pPpath1]["sid_nu"]
									,$vTrees[$pPpath1]["fcode1_st"]
									,$pfcode1
									,$file
									,$ppath1
									,$p
									,$pFsize
									,3
								]);
								// 加入資料庫
							}
						}else{
							echo $pPpath1.'path:'.$vTrees[$pPpath1].'<br />';
						}
					}
				}
			}
		}

		//echo "tree : ";
		//var_dump(json_encode($vTrees, true));
	}

	function errHandler($errno, $errstr, $errfile, $errline){
		if (!(error_reporting() & $errno)) {
			// This error code is not included in error_reporting, so let it fall
			// through to the standard PHP error handler
			return false;
		}

		//為了安全起見，不暴露出真實物理路徑，下面兩行過濾實際路徑
	    //$errfile=str_replace(getcwd()," ",$errfile);
		//$errfile=str_replace('\\', '', $errfile);
		$errstr=str_replace(getcwd()," ",$errstr);

		$pRJs1=new retJson();

		$pRJs1->err = $errno;
		array_push($pRJs1->descs, $errstr);
		array_push($pRJs1->descs, "Program in $errfile:($errline)");

		echo json_encode($pRJs1);

		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);

		/*
		switch ($errno) {
			case E_USER_ERROR:
				break;
			case E_USER_WARNING:
				break;
			case E_USER_NOTICE:
				break;
			default:
				break;
		}
		*/

		/* Don't execute PHP internal error handler */
		return true;
	}

	set_error_handler("errHandler"); 
?>