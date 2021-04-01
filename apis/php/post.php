<?php
	
$curl = curl_init();
// url
$url = 'http://spadmin.jeantech.com.tw/Reservation/addform?';
$url .= 'CharsetURL=UTF-8';

// 參數
$data = 'HousingID=Ryy9000005';
$data .= '&ContactName=吳富榮Test';
$data .= '&PhoneNumber=0989123457';
$data .= '&Date=2020/07/23';
$data .= '&Dtype=2';

// 設定curl網址
curl_setopt($curl, CURLOPT_URL, $url);

// 設定Header
//curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-formurlencoded"));
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER,0);

// 執行
$output = curl_exec($curl);
curl_close($curl);

if (!$output){
	echo 'error:<br />';
	exit;
}
echo $output;

?>