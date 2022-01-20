<?php

//header("Content-type:application/json; charset=UTF-8");

if(isset($_GET["tid"])){
	$tid = $_GET["tid"];
	if(preg_match("/[a-zA-Z]{4}-\d{5}/",$tid)==1){
		$json = file_get_contents("json/ps1_ps2_psxdatacenter.json");
		$json = json_decode($json,true);
		if(!empty($json[$tid])){
			$url = $json[$tid]["url"];
			//echo json_encode($result, JSON_UNESCAPED_UNICODE);
			if(preg_match("/games\//",$url)){
				$url = "https://psxdatacenter.com/" . $url;
			}else{
				$url = "https://psxdatacenter.com/psx2/" . $url;
			}
			$url = $url . ".html";
			echo $url;
		}
	}
}