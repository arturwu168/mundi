<?php
	require_once('lib.php');

	$pRJs1=new RetJson();
	$pDbs1=$gDbs1->connect();
	
	if (gettype($pDbs1)==='string'){
		$pRJs1->err=1;
		array_push($pRJs1->descs, $pDbs1);
		echo 'error:'.$pRJs1->err.'<br />';
		echo 'description:';
		var_dump(join('<br />', $pRJs1->descs));

	}else{
		$proot1='dload_0';
		$pSql1 = 'delete from a1_framework where fcode1_st=? and psid1_nu<>?';
		$pSth1 = $pDbs1->prepare($pSql1);
		$pSth1->execute([$proot1, 0]);

		$pSql1 = 'select mt1.sid_nu, mt1.fcode1_st, mt1.psid1_nu, mt1.code1_st from a1_framework mt1 where mt1.fcode1_st=? order by mt1.psid1_nu, mt1.code1_st';
		$pSth1 = $pDbs1->prepare($pSql1);
		$pSth1->execute([$proot1]);
		$pRows1 = $pSth1->fetchAll(PDO::FETCH_ASSOC);

		$pTrees=[];
		$pfnum1=0;
		$pfnum2=0;

		if ($pRows1){
			$ppath1='../../frameworks/dload';

			for ($i=0; $i<count($pRows1); $i++){
				//echo $pRows1[$i]["code1_st"]."<br>";
				//echo $pRows1[$i]["sid_nu"]."<br>";

				$pTrees[$pRows1[$i]["code1_st"]]=[
					  "fcode1_st" => $pRows1[$i]["fcode1_st"]
					, "psid1_nu" => (string)$pRows1[$i]["psid1_nu"]
					, "sid_nu" => (string)$pRows1[$i]["sid_nu"]
				];

				//array_push($pTrees, array("orderID" => 12345));
			}

			//var_dump($pTrees);
			//echo '<br />';

			ff2dbf($ppath1, $ppath1, $arr1, $pDbs1, $pTrees);
		}else{
		
		}

		echo 'files:'.$pfnum1;
		echo "<br />";
		echo 'folders:'.$pfnum2;
		echo "<br />";

	}


	$pDbs1=null;
?>