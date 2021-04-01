<?php
    require_once('lib.php');
    require_once('dbs.php');

    ini_set('display_errors', 0);

    try
    {

        $pSql4 = 'select * from z9sysdbb001';
        $pQuery1=$gDb['isd_isdmain']->query($pSql4);
        $pData1s = $pQuery1->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pData1s as $vData1){
            echo $vData1['a0_var_name1'] . "<br>";
        }

        foreach($gDb as $vObj1){
            $vObj1=null;
        }
        
    }
    catch(PDOException $err)
    {
        //Œ‘ÈëåeÕ`ÙYÁÏ±í
        trigger_error($err);
	}
?>