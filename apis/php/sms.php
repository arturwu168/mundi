<?php
	
$curl = curl_init();
// url
$url = 'https://smsapi.mitake.com.tw/b2c/mtk/SmSend?';
$url .= 'CharsetURL=UTF-8';
// parameters
$data = 'username=22006274';
$data .= '&password=Jean22006274';
$data .= '&dstaddr=0987190393';
$data .= '&smbody=��ӍSmSend�yԇ';
// �O��curl�Wַ
curl_setopt($curl, CURLOPT_URL, $url);
// �O��Header
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-formurlencoded"));
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_HEADER,0);
// ����
$output = curl_exec($curl);
curl_close($curl);

if (!$output){
	echo 'error:<br />';
}
echo $output;	

?>