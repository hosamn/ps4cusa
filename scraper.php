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
$get_flag = true;

if(isset($_GET["url"]) && preg_match("/^https?:/",$_GET["url"]) && $get_flag){
	$url = $_GET["url"];
	$check_urls =[
		"https://www.jp.playstation.com/software/",
		"https://www.jp.playstation.com/games/",
		"https://gamefaqs.gamespot.com/ps4/",
		"https://gamefaqs.gamespot.com/vita/",
		"https://www.google.com/search",
		"https://search.yahoo.co.jp/search",
		"http://tmdb.np.dl.playstation.net/",
		"http://gs2-sec.ww.prod.dl.playstation.net/",
		"http://gs-sec.ww.np.dl.playstation.net/",
		"http://gs2.ww.prod.dl.playstation.net/",
		"https://sgst.prod.dl.playstation.net/",
		"https://a0.ww.np.dl.playstation.net/",
		"http://gs-sec.ww.np.dl.playstation.net/",
		"https://store.playstation.com/store/api/chihiro/",
		"https://store.playstation.com/valkyrie-api/",
		"http://redump.org/",
		"https://psxdatacenter.com/",
		"http://b0.ww.np.dl.playstation.net/",
		"http://gs2.ww.prod.dl.playstation.net/",
		"http://gs.ww.np.dl.playstation.net/",
	];
	$hit = false;
	foreach($check_urls as $check_url){
		if(strpos($url, $check_url)===0){
			$hit = true;
			break;
		}
	}
	if(!$hit){
		if(preg_match("/^https?:\/\/[^\/:]*\.dl\.playstation\.net/", $url)){
			$hit = true;
		}
	}

	if($hit){
		$options["ssl"]["verify_peer"]=false;
		$options["ssl"]["verify_peer_name"]=false;
		if(!isset($_GET["no_ua"])){
			$options = [
				"http" => [
					"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
				],
			];
		}
		$response = file_get_contents($url, false, stream_context_create($options));
		echo $response;
	}else{
		/* kood.infoで使用しているスターサーバーのエラーログが1日でリセットされるため、とりあえずアクセスログに記録する */
		$empty_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER["SCRIPT_NAME"]) . "/empty?url=" . $url;
		file_get_contents($empty_url);

		throw new rejectURLException($url);
	}

}else{
	echo "";
}

class rejectURLException extends Exception{
	public function __construct($url){
		$this->message = "Invalid URL for scraping. " . $url;
	}
}
