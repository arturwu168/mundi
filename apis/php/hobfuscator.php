<?php
	use MatthiasMullie\Minify;
	require_once ('thirds/matthiasmullie/vendor/autoload.php');
	require_once('thirds/hunter-php-javascript-obfuscator-master/original/HunterObfuscator.php');
	require_once('thirds/hunter-php-javascript-obfuscator-master/modified/obfuscator.php');

	$jsCode = file_get_contents("../../jss/isd_src.js"); //Simple JS code

	$minifier = new Minify\JS();
	$minifier->add($jsCode);
	$jsCode=$minifier->minify();


	$hunter = new HunterObfuscator($jsCode); //Initialize with JS code in parameter
	$obsfucated = $hunter->Obfuscate(); //Do obfuscate and get the obfuscated code
	echo "<textarea style=\"width:100%; height:100%\">" . $obsfucated . "</textarea >";

	//file_put_contents("../../jss/isd.js", $obsfucated); //Simple JS code
	//file_put_contents("../../jss/isd.js", $jsCode); //Simple JS code

	//$jsCode = file_get_contents("../../sys_src.js"); //Simple JS code

	//$hunter = new HunterObfuscator($jsCode); //Initialize with JS code in parameter
	//$obsfucated = $hunter->Obfuscate(); //Do obfuscate and get the obfuscated code
	//echo "<textarea style=\"width:100%; height:100%\">" . $obsfucated . "</textarea >";

	//file_put_contents("../../sys.js", $obsfucated); //Simple JS code
	//file_put_contents("../../sys.js", $jsCode); //Simple JS code

?>