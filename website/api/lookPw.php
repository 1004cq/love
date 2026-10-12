<?php
/*
 * @Page: "点点滴滴&相册列表 密码查询".
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2024-05-11
 * @Description: 花有重开日 人无再少年
 * Copyright (c) 2022-2024 by Ki All Rights Reserved.
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

@$Id = $_GET['id'];
@$checkPw = $_GET['encrypCode'];
@$checkType = $_GET['encrypType'];

if ($Id == ''|| $checkPw == '' || $checkType =='') {
    die("参数错误");
}

include_once '../admin/Database.php';

if($checkType == "article"){
    $SelectData = "SELECT * FROM article WHERE id = ? limit 1";
}else if ($checkType == "loveImg") {
   $SelectData = "SELECT * FROM loveImg WHERE img_code = ? limit 1";
}

$stmt_select = $conn->prepare($SelectData);
$stmt_select->bind_param("i",$Id);
if($checkType == "article"){
    $stmt_select->bind_result($id,$articletext,$articletime,$articletitle,$articlename,$ip_city,$encryptionSwitch,$ResPw);
}else if($checkType == "loveImg"){
    $stmt_select->bind_result($id,$imgDatd,$imgText,$imgUrl,$upload_name,$img_code,$encryptionSwitch,$ResPw);
}

$result = $stmt_select->execute();
if (!$result)
    echo "错误信息：" . $stmt_select->error;

$stmt_select->fetch();

$PhotoCover = $imgUrl;

$stmt_select->close();

// 判断标识
if ($ResPw == $checkPw) {
    $state = true;
}else{
    $state = false;
}

//标识存在则设置Token
if ($state) {
    $randomBytes = random_bytes(20);
    $tokenHex = bin2hex($randomBytes);
}else{
    $tokenHex = null;
}

$h1 = "<view class=\"h1\">";
$h2 = "<view class=\"h2\">";
$h3 = "<view class=\"h3\">";
$h4 = "<view class=\"h4\">";
$h5 = "<view class=\"h5\">";
$h6 = "<view class=\"h6\">";
$img = "<image";
$imgg = "\"/>";
$vid = "<video";
$vidg = "</video>";
$jiewei = "</view>";
$bol = "<text class=\"blod\">";
$boldg ="</text>";
$sss ="<text class=\"shanchu\">";
$xieg ="<text class=\"xie\">";
$h1ww =  preg_replace("/<h1>/",$h1,$articletext);
$h2ww =  preg_replace("/<h2>/",$h2,$h1ww);
$h3ww =  preg_replace("/<h3>/",$h3,$h2ww);
$h4ww =  preg_replace("/<h4>/",$h4,$h3ww);
$h5ww =  preg_replace("/<h5>/",$h5,$h4ww);
$h6ww =  preg_replace("/<h6>/",$h6,$h5ww);
$hjw =   preg_replace("[</h6>|</h5>|</h4>|</h3>|</h2>|</h1>]",$jiewei,$h6ww);
$vidww =  preg_replace("/<iframe/",$vid,$hjw);
$vidwc =  preg_replace("/<\/iframe>/",$vidg,$vidww);
$blodww =  preg_replace("/<b>/","",$vidwc);
$blodwc =  preg_replace("/<\/b>/","",$blodww);
$shanchu =  preg_replace("/<s>/",$sss,$blodwc);
$shanwc =  preg_replace("/<\/s>/","</text>",$shanchu);
$xie =  preg_replace("/<i>/",$xieg,$shanwc);
$xiewc =  preg_replace("/<\/i>/","</text>",$xie);

if ($state) {
    if($checkType == "article"){
        $ResArr[] = array(
            "ResId" => intval($Id),
            "state" => $state,
            "Token" => $tokenHex,
            "articletext" => $xiewc,
            "articletitle" => htmlspecialchars_decode($articletitle, ENT_QUOTES),
            "articlename" => $articlename,
            "articletime" => date('Y-m-d H:i', $articletime),
            "ipcity" => $ip_city,
        );
    }else if($checkType == "loveImg"){
        $PhotoData = "SELECT * FROM Photolist WHERE photo_code = ?";
        $stmt = $conn->prepare($PhotoData);
        $stmt->bind_param("i", $Id);
        $stmt->bind_result($id, $photo_id, $photo_code, $photo_text,$photo_url,$photo_date,$photo_byname,$photo_type,$VideoCover);
        $stmt->execute();
        while ($stmt->fetch()) {
            $PhotoArr[] = array(
                "id" => $id,
                "photo_id" => $photo_id,
                "photo_code" => $photo_code,
                "photo_text" => $photo_text,
                "photo_url" => $photo_url,
                "photo_date" => $photo_date,
                "photo_byname" => $photo_byname,
                "photo_type" => $photo_type,
                "VideoCover" => $VideoCover,
            );
        }
        if($photo_code == ''){
            $photo_code = $Id;
        }
            $ResArr[] = array(
                
                "ResId" => intval($photo_code),
                "state" => $state,
                "Token" => $tokenHex,
                "PhotoList" => $PhotoArr
            );
    }
}else{
    $ResArr[] = array(
            "ResId" => intval($Id),
            "state" => $state,
            "Token" => $tokenHex,
        );
}





$json_arr =  json_encode($ResArr);
if ($json_arr == "null") {
    echo 0;
}else{
    echo format_json($json_arr);
}



//json格式化

function format_json($json, $html = false) {
    $tabcount = 0;
    $result = '';
    $inquote = false;
    $ignorenext = false;

    if ($html) {
        $tab = "&nbsp;&nbsp;&nbsp;";
        $newline = "<br/>";
    } else {
        $tab = "\t";
        $newline = "\n\n";
    }

    for($i = 0; $i < strlen($json); $i++) {
        $char = $json[$i];

        if ($ignorenext) {
            $result .= $char;
            $ignorenext = false;
        } else {
            switch($char) {
                case '{':
                    $tabcount++;
                    $result .= $char . $newline . str_repeat($tab, $tabcount);
                    break;
                case '}':
                    $tabcount--;
                    $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
                    break;
                case ',':
                    $result .= $char . $newline . str_repeat($tab, $tabcount);
                    break;
                case '"':
                    $inquote = !$inquote;
                    $result .= $char;
                    break;
                case '\\':
                    if ($inquote) $ignorenext = true;
                    $result .= $char;
                    break;
                default:
                    $result .= $char;
            }
        }
    }

    return $result;
}