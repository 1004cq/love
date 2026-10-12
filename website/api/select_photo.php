<?php
/*
 * @Page: "相册数据获取".
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2024-02-16
 * @Description: 花有重开日 人无再少年
 * Copyright (c) 2022-2024 by Ki All Rights Reserved.
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: application/json; charset=utf-8");

@$code = $_GET['code'];



if (@$code == '') {
    die("参数错误");
}
include_once '../admin/Database.php';
include_once '../admin/connect.php';
include_once '../admin/Function.php';

$SelectData = "SELECT encryptionSwitch FROM loveImg WHERE img_code = ? limit 1";
$stmt_select = $conn->prepare($SelectData);
$stmt_select->bind_param("i",$code);
$stmt_select->bind_result($encryptionSwitch);
$stmt_select->execute();
$stmt_select->fetch();
if ($encryptionSwitch == 1) {
        $PhotoArr[] = array(
                "code" => $code,
                "info" => "当前相册已被加密",
                "time" => date("Y-m-d H:i:s",time())
        );
}else{
$stmt_select->close();

$AnonAvatar = "select anonymousArr from leavSet";
$AnonAvatarRes = mysqli_query($connect, $AnonAvatar);
$Anoninfo = mysqli_fetch_array($AnonAvatarRes);
$AnonAvatarArr = $Anoninfo['anonymousArr'];

$PhotoData = "SELECT * FROM Photolist WHERE photo_code = ?";
$stmt = $conn->prepare($PhotoData);
$stmt->bind_param("i", $code);
$stmt->bind_result($id, $photo_id, $photo_code, $photo_text,$photo_url,$photo_date,$photo_byname,$photo_type,$VideoCover);
$result = $stmt->execute();
if (!$result)
    echo "错误信息：" . $stmt->error;
    
    while ($stmt->fetch()) {
        if($text['boy'] == $photo_byname) {
            $thisAvatar = DetermineAvatarAddress(1);
        }else if($text['girl'] == $photo_byname){
            $thisAvatar = DetermineAvatarAddress(0);
        }else{
            $AnonAvatarData = ExtractURL($AnonAvatarArr);
            if (is_array($AnonAvatarData) && !empty($AnonAvatarData)) {
                $randomKey = array_rand($AnonAvatarData);
                $thisAvatar = $AnonAvatarData[$randomKey];
            } else {
                $thisAvatar = $AnonAvatarData;
            }
        }
        
            $PhotoArr[] = array(
                "id" => $id,
                "photo_id" => $photo_id,
                "photo_code" => $photo_code,
                "photo_text" => $photo_text,
                "photo_url" => $photo_url,
                "photo_date" => $photo_date,
                "photo_byname" => $photo_byname,
                "photo_type" => $photo_type,
                'up_avatar' => $thisAvatar,
                "VideoCover" => $VideoCover
            );
    }
}



// print_r($PhotoArr);


$json_arr =  json_encode($PhotoArr);
if ($json_arr == "null") {
    echo "0";
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