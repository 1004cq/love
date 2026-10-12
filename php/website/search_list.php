<?php
error_reporting(0);
@$search_info = $_POST['search_info'];
@$search_Type = $_POST['search_Type'];
if (@$search_info == '') {
    die("参数错误");
}

if ($search_info !== "All" && $search_info !== "Success" && $search_info !== "Fail") {
    $search_info = "%$search_info%";
}

switch (@$search_info) {
    case $search_info  == "All":
        $listData = "SELECT * FROM lovelist";
        break;
    case $search_info  == "Success":
        $listData = "SELECT * FROM lovelist WHERE icon = 1";
        break;
    case $search_info  == "Fail":
        $listData = "SELECT * FROM lovelist WHERE icon = 0";
        break;
    default:
        $listData = "SELECT * FROM lovelist WHERE eventname LIKE ?";
}


include_once 'admin/Database.php';
include_once 'admin/Function.php';



$stmt = $conn->prepare($listData);
$stmt->bind_param("s", $search_info);
$stmt->bind_result($id, $icon, $eventname, $imgurl);
    
$result = $stmt->execute();
if (!$result)
    echo "错误信息：" . $stmt->error;
    
    
    while ($stmt->fetch()) {
        
        if(@$search_Type){
            $Listurls = ExtractURL($imgurl);
            $listurlObjects = [];
            foreach ($Listurls as $url) {
                $listurlObjects[] = ['url' => $url];
            }
        }else{
            $listurlObjects = ExtractURL($imgurl);
        }

        
        $listArr[] = array(
            "id" => $id,
            "icon" => $icon,
            "eventname" => $eventname,
            "imgurl" => $listurlObjects,
            "listurl" => $listurlObjects,
        );
    }
    


$json_arr =  json_encode($listArr);
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