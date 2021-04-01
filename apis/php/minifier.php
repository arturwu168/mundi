<?php
	use MatthiasMullie\Minify;
	require_once ('apis/php/thirds/matthiasmullie/vendor/autoload.php');

	$pTmain="_u-isd";

	$pSfiles=[
		""
		, "csss"
		, "jss"
		, "loads/simple/001/login/001"
		, "loads/simple/001/login/001/3b92c30001"
		, "loads/simple/001/login/001/i18n"
		, "rcoms/simple/001/button/001"
		, "rcoms/simple/001/button/001/55acee001"
		, "rcoms/simple/001/button/001/transp0001"
		, "rcoms/simple/001/button/001/i18n"
	];

	foreach ($pSfiles as $vVal1) {

		//建立資料夾
		if (true){
			$pTpaths= explode("/",$vVal1);

			$pPathT1="";
			foreach ($pTpaths as $val1) {
				if ($val1!==""){
					if ($pPathT1==""){
						$pPathT1=$val1;
					}else{
						$pPathT1.="/".$val1;
					}
					//echo "建立 ".$pTmain."/".$pPathT1." 資料夾<br>";

					if (!file_exists($pTmain."/".$pPathT1)) {
						mkdir($pTmain."/".$pPathT1, 0775, true);
					}
			
				}
			}
		}

		$file_ext = array(
			'.',
			'..', 
			'.vs'
		);

		$pPathT1='/isd/'.$vVal1;

		$fileList = array_diff(scandir($pPathT1), $file_ext);

		foreach($fileList as $filename){
			$pPathT1='isd/'.$vVal1;
			$pPathT2=$vVal1."/";

			if (!is_dir($filename)){
				if ($vVal1===""){
					$pPathT1='isd';
					$pPathT2="";
				}

				$pFileExt1=pathinfo($filename, PATHINFO_EXTENSION);

				if (strpos($filename, "min")<=0){
					if ($pFileExt1==="css"){
						$pSrc1=$pPathT2.$filename;
						$pTrg1=$pTmain.'/'.$pSrc1;

						echo $pSrc1." write to ";
						echo $pTrg1."<br />";

						$css=file_get_contents($pSrc1);
						$minifier = new Minify\CSS();
						$minifier->add($css);
						$minifier->minify($pTrg1);

					}else if ($pFileExt1==="js"){
						$pSrc1=$pPathT2.$filename;
						$pTrg1=$pTmain.'/'.$pSrc1;

						echo $pSrc1." write to ";
						echo $pTrg1."<br />";

						$js=file_get_contents($pSrc1);
						$minifier = new Minify\JS();
						$minifier->add($js);
						$minifier->minify($pTrg1);

					}else if ($pFileExt1==="html" || $pFileExt1==="json"){
						$pSrc1=$pPathT2.$filename;
						$pTrg1=$pTmain.'/'.$pSrc1;

						echo $pSrc1." copy to ";
						echo $pTrg1."<br />";

						copy($pSrc1, $pTrg1);
					}
				}
			}	   
		}

		echo "======================================================================================================================================<br />";

	}

?>

