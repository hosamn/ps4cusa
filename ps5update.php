<?php
header("Content-type:application/json; charset=UTF-8");

if(isset($_GET["id"])){
    $tid = $_GET["id"];
    $tsv_url = "https://raw.githubusercontent.com/1jtp8sobiu/ps5-pkg/master/PS5_XML.tsv";
    //$tsv_url = "http://localhost:50000/test/PS5_XML.tsv";
    $tsv = new NoRewindIterator(new SplFileObject($tsv_url));
    $tsv->setFlags(SplFileObject::READ_CSV);
    $tsv->setCsvControl("\t");

    $tid_reg = "/[a-zA-Z]{4}-?\d{5}_?([a-zA-Z0-9]{2})?(\s|　)?/";
    foreach($tsv as $row){
        $cid = $row[0];
        if(preg_match($tid_reg, $cid, $tid_match)){
            if(strcmp($tid, $tid_match[0])==0){
                $match_row = $row;
                break;
            }
        }
    }

    if(isset($match_row)){
        if(count($match_row)==3){
            $json = array(
                "cid" => $match_row[0],
                "title" => $match_row[1],
                "url" => $match_row[2],
            );
            $json = json_encode($json,JSON_UNESCAPED_UNICODE);
            echo $json;
        }else if(count($match_row)==2){
            $title = $match_row[1];
            $url_index = strpos(($title), "https://");
            if($url_index){
                $new_url = substr($title, $url_index);
                $title_len = strlen($title);
                $new_url_len = strlen($new_url);
                $new_title = substr($title, 0, $title_len - $new_url_len);
                $json = array(
                    "cid" => $match_row[0],
                    "title" => $new_title,
                    "url" => $new_url,
                );
                $json = json_encode($json,JSON_UNESCAPED_UNICODE);
                echo $json;
            }else{
                echo "error4";    
            }
        }else{
            echo "error3";
        }

    }else{
        echo "error2";
    };

}else{
    echo "error1";
}