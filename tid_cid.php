<?php
header("Content-type:application/json; charset=UTF-8");

if(isset($_GET["tid"])){
	$tid = $_GET["tid"];
	if(preg_match("/[a-zA-Z]{4}\d{5}/",$tid)){
		$json = file_get_contents("json/ps3_psp_psv_tid_cid.json");
		$json = mb_convert_encoding($json, "UTF8", "ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN");
		$json = json_decode($json,true);
		//var_dump($json);
		if(!empty($json[$tid])){
			$result = $json[$tid];
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}
}