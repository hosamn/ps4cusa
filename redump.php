<?php
$get_flag = true;
if(isset($_SERVER["HTTP_REFERER"])){
	$ref = $_SERVER["HTTP_REFERER"];
	if(!preg_match("/^(http:\/\/kood\.info\/|http:\/\/localhost\:?)/",$ref)){
		$get_flag = false;
	}
}else{
	$get_flag = false;
}
//$get_flag = true;

if(isset($_GET["tid"]) && $get_flag){
	$data = array(
		"quicksearch" => $_GET["tid"]
	);
	$data = http_build_query($data, "", "&");
	$options = array(
			"http" => array(
				"method"=> "POST",
				"header"=> "Content-Type: application/x-www-form-urlencoded",
				"content" => $data
			)
	);
	$context = stream_context_create($options);
	$response = file_get_contents("http://redump.org/results/", false, $context);
	echo $response;
}else{
    echo "";
}