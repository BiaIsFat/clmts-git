<?php

 require_once('config.php'); 
 $appid = APPID;
 $appsecret = APPSECRET;
 
 $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
 
 $output = https_request($url);
 $jsoninfo = json_decode($output, true);
 $access_token = $jsoninfo["access_token"];

 //临时
 //$qrcode = '{"expire_seconds": 194400, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 10000}}}';
 //永久
 $qrcode = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "clmts"}}}';
 
 
 $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
 $result = https_request($url,$qrcode);
 $jsoninfo = json_decode($result, true);
 $ticket = $jsoninfo["ticket"];
 
 $qrurl = $jsoninfo["url"];
 var_dump($ticket);  
 var_dump($qrurl);
 
 function https_request($url, $data = null)
 {
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, $url);
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
     if (!empty($data)){
         curl_setopt($curl, CURLOPT_POST, 1);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
     }
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     $output = curl_exec($curl);
     curl_close($curl);
     return $output;
 }

?>
