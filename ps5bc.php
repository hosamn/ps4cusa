<?php
header("Content-type:application/json; charset=UTF-8");

if(isset($_GET["tid"])){
	$tid = $_GET["tid"];
	if(preg_match("/[a-zA-Z]{4}\d{5}/",$tid)){
		
		$context = stream_context_create();
		stream_context_set_option($context, "http", "ignore_errors", true);
		$url = "https://raw.githubusercontent.com/andshrew/supreme-enigma/master/docs/PS5-BC-Status.json";
		$json = file_get_contents($url, false, $context);
		
		if(!preg_match("/404: Not Found/",$json)){
			$json = json_decode($json,true);
			$index = array_search($tid,array_column($json,"npTitleId"));
			if(strcmp($index, "")!=0){
				echo json_encode($json[$index],JSON_UNESCAPED_UNICODE);
			}else{
				echo "error4";
			}
		}else{
			echo "error3";
		}
		
	}else{
		echo "error2";
	}
}else{
	echo "error1";
}