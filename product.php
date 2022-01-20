<?php
header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
header("Cache-Control:pre-check=0","post-check=0",false);
header("Pragma:no-cache");
error_reporting(0);
require_once "simple_html_dom.php";
date_default_timezone_set('Asia/Tokyo');
header("Content-type:application/json; charset=UTF-8");

if(isset($_GET["code"]) && isset($_GET["cid"])){
	try{
		$code = $_GET["code"];
		$cid = $_GET["cid"];
		$cid_reg = " /^[a-zA-Z]{2}\d{4}-[a-zA-Z]{4}\d{5}_[a-zA-Z0-9]{2}-[a-zA-Z0-9]{16}$/";
		if(strlen($code)>4 && preg_match($cid_reg, $cid)){
			$url = "https://store.playstation.com/" . $code . "/product/" . $cid;
			
			$options = [
				"http" => [
					"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36",
				],
			];
			
			/* 
				2020年10月28日時点で、kood.infoで使用しているスターサーバーでは製品ページの取得を試みると
				旧仕様のページが返ってくる事があり、その場合に取得を最大5回繰り返す事にした。
				(新仕様への移行段階のためこういう症状が発生している？)
				おそらく、将来的には繰り返しは不要になり、一度のアクセスで済むようになると思われる。
			*/
			$html = @file_get_contents($url,false,stream_context_create($options));
			if(strpos($html, '<html dir="ltr">')){
				for($i=0; $i<5; $i++){
					if(strpos($html, '<html dir="ltr">')){
						$html = @file_get_contents($url,false,stream_context_create($options));
					}else{
						$i = 100;
					}
				}
			}
			if(!strpos($html, '<html dir="ltr">')){
				$html = str_get_html($html);
			}else{
				$html = "";
			}
			
			//echo $html;
			
			if($html!=""){
				//echo "ok";
				
				$pgt_json = null;
				$pbi_json = null;
				$pc_json = null;
				$pcn_json = null;
				$pcr_json = null;
				$pu_json = null;
				$pi_json = null;
				//$ppl_json = null;
				$json_array = array();
				
				$pgt = $html->find(".pdp-game-title");
				if(count($pgt)==2){
					if($pgt[0]->innertext != ""){
						$pgt_json = $pgt[0]->find("script",0)->innertext;
						$json_array["pdp-game-title"] = json_decode($pgt_json);
					}
				}

				$pbi = $html->find(".pdp-background-image");
				if(count($pbi)==1){
					if($pbi[0]->innertext != ""){
						$pbi_json = $pbi[0]->find("script",0)->innertext;
						$json_array["pdp-background-image"] = json_decode($pbi_json);
					}
				}
				
				$pc = $html->find(".pdp-cta");
				if(count($pc)==4){
					if($pc[0]->innertext != ""){
						$pc_json = $pc[0]->find("script",0)->innertext;
						$json_array["pdp-cta"] = json_decode($pc_json);
					}
				}
				
				$pcn = $html->find(".pdp-compatibility-notices");
				if(count($pcn)==2){
					if($pcn[0]->innertext != ""){
						$pcn_json = $pcn[0]->find("script",0)->innertext;
						$json_array["pdp-compatibility-notices"] = json_decode($pcn_json);
					}
				}

				$pcr = $html->find(".pdp-content-rating");
				if(count($pcr)==2){
					if($pcr[0]->innertext != ""){
						$pcr_json = $pcr[0]->find("script",0)->innertext;
						$json_array["pdp-content-rating"] = json_decode($pcr_json);
					}
				}

				$pu = $html->find(".pdp-upsells");
				if(count($pu)==1){
					if($pu[0]->innertext != ""){
						$pu_json = $pu[0]->find("script",0)->innertext;
						$json_array["pdp-upsells"] = json_decode($pu_json);
					}
				}

				$pi = $html->find(".pdp-info");
				if(count($pi)==1){
					if($pi[0]->innertext != ""){
						$pi_json = $pi[0]->find("script",0)->innertext;
						$json_array["pdp-info"] = json_decode($pi_json);
					}
				}
				
				if(count($json_array)>2){
					echo json_encode($json_array,JSON_UNESCAPED_UNICODE);
				}else{
					echo "error5";
				}
			
			}else{
				echo "error4";
			}
		}else{
			echo "error3";
		}
	}catch(Exception $e){
		echo "error2";
	}
}else{
	echo "error1";
}
?>