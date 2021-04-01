<?php
	ini_set('display_errors', 0);

	header('Content-Type: text/html; charset=utf-8');

	require_once('lib.php');
	require_once('dbs.php');

	require_once ('thirds/snowflake/vendor/autoload.php');

	//phpinfo();
	$json  =  '
{
  "title": "Person",
  "type": "object",
  "properties": {
    "name": {
      "type": "string",
      "description": "First and Last name",
      "minLength": 4,
      "default": "Jeremy Dorn"
    },
    "age": {
      "type": "integer",
      "default": 25,
      "minimum": 18,
      "maximum": 99
    },
    "favorite_color": {
      "type": "string",
      "format": "color",
      "title": "favorite color",
      "default": "#ffa500"
    },
    "gender": {
      "type": "string",
      "enum": [
        "male",
        "female"
      ]
    },
    "location": {
      "type": "object",
      "title": "Location",
      "properties": {
        "city": {
          "type": "string",
          "default": "San Francisco"
        },
        "state": {
          "type": "string",
          "default": "CA"
        },
        "citystate": {
          "type": "string",
          "description": "This is generated automatically from the previous two fields",
          "template": "{{city}}, {{state}}",
          "watch": {
            "city": "location.city",
            "state": "location.state"
          }
        }
      }
    },
    "pets": {
      "type": "array",
      "format": "table",
      "title": "Pets",
      "uniqueItems": true,
      "items": {
        "type": "object",
        "title": "Pet",
        "properties": {
          "type": {
            "type": "string",
            "enum": [
              "cat",
              "dog",
              "bird",
              "reptile",
              "other"
            ],
            "default": "dog"
          },
          "name": {
            "type": "string"
          }
        }
      },
      "default": [
        {
          "type": "dog",
          "name": "Walter"
        }
      ]
    }
  }
}
	' ;  
	$json_Class =json_decode($json);  

	//print_r( $json_Class );   
	
    //print_r( '<br><br>');   

	//print_r( json_encode($json_Class, JSON_UNESCAPED_UNICODE) );   

	//print_r( $json_Array );   



	set_time_limit(0);

	//$pRJs1=new dbRetJson();
	//$pDbs1=$gDbs1->connect();

	//$pStr1=guid('s000001d000001t00001');
	//$pStr2=guid('s000001d000001t00002');
	exit;

	ini_set("memory_limit","1000M");

	//echo com_create_guid().'<br>';


	$pSql4 = 'insert into z9syspeo001(z9_chr_uuid1, z8_dtu_cdtime1, z8_dtu_lmdtime1) values ';

	
	for ($i=0; $i<1; $i++){
		$pStr1=session_create_id();
		//guid('s1d1t1');
		$sTime1=microtime(true);
		
		echo $pStr1.'<br />';
		//echo $sTime1.'<br />';
		if ($i===0){
			//$pSql4.='(unhex(\''.$pStr1.'\'), '.$sTime1.', '.$sTime1.')';
			$pSql4.='(\''.$pStr1.'\', '.$sTime1.', '.$sTime1.')';
		}else{
			//$pSql4.=', (unhex(\''.$pStr1.'\'), '.$sTime1.', '.$sTime1.')';
			$pSql4.=', (\''.$pStr1.'\', '.$sTime1.', '.$sTime1.')';
		}

	}
	//$pDbs1->exec($pSql4);
	echo 'Fnish~~<br>';
	//echo $pSql4.'<br>';
	
	$pDbs1=null;

	set_time_limit(90);

?>