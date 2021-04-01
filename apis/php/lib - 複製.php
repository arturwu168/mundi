<?php
	//require_once('dbs.php');

	if (!session_status()){
		session_start();
	}

	ini_set('date.timezone','Asia/Taipei');

	function guid($vStr1=''){
		$uuid = md5($vStr1.str_replace('.', '', uniqid(mt_rand(), true)));
		return $uuid;
	}

	function fGuid($vVal1=0, $vVal2=0) { 
	    list($s1, $s2) = explode(' ', microtime()); 
		$pVal1=$s2.substr(str_replace('0.', '', $s1), 0, 6);

		if ($vVal1===$pVal1){
			echo '<font style="color:#F00">same value:'.$vVal1.'==='.$pVal1.' times:'.($vVal2+1).'</font><br />';
			$vVal2++;
			if ($vVal2==1000){
				$pVal1=-1;
				echo '<font style="color:#F00">guid '.$vVal1.' over '.$vVal2.' times same exit</font><br />';
				//return($pVal1);
				//exit;
			}else{
				$pVal1=fGuid($pVal1, $vVal2);
			}
		}

		return($pVal1);
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

	

?>