<?php

/*
 * Project: LG_NewUi WXAPP-API v3.0
 * Author: Ki
 * Last Updated: 2024-12-21
 * Description: 小手一牵 岁岁年年
 * 
 * Copyright (c) 2022-2024 by Ki. All Rights Reserved.
 * 
 * License: This software is licensed for both personal and commercial use under specific terms.
 * Redistribution, modification, or resale without explicit written permission is strictly prohibited.
 * Unauthorized use, copying, or redistribution will result in immediate termination of support and updates.
 * 
 * Documentation and License: https://lguiy.kikiw.cn/wiki
 * 
 * * Warning: This is an original work, and all copyright belongs to LG_NewUi.
 * Any attempt to scrape, copy, sell, or redistribute this software without permission is strictly forbidden.
 * Violators will face immediate legal action, blacklisting, and permanent cessation of future updates.
 * 
 * Development requires substantial time and effort. We appreciate your respect for the work involved and your support.
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

include_once '../admin/connect.php';
include_once '../admin/Database.php';
include_once '../admin/Function.php';
include_once '../music.php';


// ini_set('display_errors', 1);
// error_reporting(E_ALL);

class APIDataGenerator
{
    private $connect;
    private $conn;
    private $LoversName;

    public function __construct($connect, $conn, $LoversName)
    {
        $this->connect = $connect;
        $this->conn = $conn;
        $this->LoversName = $LoversName;
    }

    public function generateData()
    {

        $stateResults = [];

        $stateResults['textData'] = $this->updateTextData();
        $stateResults['loveDayData'] = $this->updateLoveDayData();
        $stateResults['articleData'] = $this->updateArticleData();
        $stateResults['imageData'] = $this->updateImageData();
        $stateResults['eventData'] = $this->updateEventData();
        $stateResults['leaveData'] = $this->updateLeaveData();

        echo json_encode($stateResults, JSON_UNESCAPED_UNICODE);
    }

    private function updateTextData()
    {
        try {
            $results = $this->fetchTextData();
            
            $configResults = $this->fetchConfigData();
            
            $LeavSetInfo = $this->fetchLeavSetData();
    
            $finalResults = array_merge($results, $configResults, $LeavSetInfo);
    
            $DataResults = json_encode($finalResults, JSON_UNESCAPED_UNICODE);
    
            $this->writeJson('data.json', $DataResults);
    
            return ['status' => 'success', 'message' => '更新站点基本信息和配置成功'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => '更新站点基本信息和配置失败: ' . $e->getMessage()];
        }
    }


    private function updateLeaveData()
    {
        try {
            // 获取留言设置
            $leavSet = "SELECT * FROM leavSet";
            $Set = mysqli_query($this->connect, $leavSet);
            if (!$Set) {
                throw new Exception("Error executing query: " . mysqli_error($this->connect));
            }

            // 获取设置数据
            $Setinfo = mysqli_fetch_array($Set);
            if (!$Setinfo) {
                throw new Exception("无法获取留言设置数据");
            }

            $anonymousArr = $Setinfo['anonymousArr'];
            $jiequ = $Setinfo['jiequ'];
            $CutOffSwitch = $Setinfo['CutOffSwitch'];

            if ($CutOffSwitch == 2) {
                $liuyan = "SELECT * FROM leaving WHERE switch = 1 ORDER BY time DESC";
            }else{
                $liuyan = "SELECT * FROM leaving WHERE switch = 1 ORDER BY time DESC LIMIT ?";
            }
            
            $stmt = $this->conn->prepare($liuyan);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }

            if ($CutOffSwitch == 1) {
               $stmt->bind_param("i", $jiequ);
            }
            $stmt->bind_result($id, $name, $qq, $text, $time, $ip, $city, $switch, $Os_info, $Br_info);

            $lyresult = $stmt->execute();
            if (!$lyresult) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }

            // 解析匿名留言头像
            $anonymousArr = explode("\n", $anonymousArr);
            $anonymousList = array_map('trim', $anonymousArr);

            // 处理留言数据
            $arr = [];
            while ($stmt->fetch()) {

                if ($qq == "anon") {
                    $qq = "匿名";
                }

                $safeText = parseEmojis($text, 3);

                $arr[] = [
                    "qq" => $qq,
                    "name" => $name,
                    "time" => $time,
                    "text" => $safeText,
                    "HideText" => "<p style='max-width: 240px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;'>" . $safeText . "<p>",
                    "city" => $city,
                    "Os_info" => $Os_info,
                    "Br_info" => $Br_info,
                    "DiyPic" => $anonymousList[array_rand($anonymousList)],
                ];
            }

            // 判断是否有留言数据
            if (!empty($arr)) {
                $lydata = json_encode($arr, JSON_UNESCAPED_UNICODE);
                file_put_contents('leav.json', $this->formatJson($lydata));
                return ['status' => 'success', 'message' => '更新留言API数据成功'];
            } else {
                throw new Exception("没有找到留言数据");
            }
        } catch (Exception $e) {
            // 捕获异常并返回错误信息
            return ['status' => 'error', 'message' => '更新留言数据失败: ' . $e->getMessage()];
        }
    }


    private function updateLoveDayData()
    {
        try {
            $arrLoveTime = $this->fetchLoveDayData();
            $loveTime = json_encode($arrLoveTime, JSON_UNESCAPED_UNICODE);
            $this->writeJson('loveTime.json', $loveTime);
            return ['status' => 'success', 'message' => '更新恋爱日期API成功'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => '更新恋爱日期json失败: ' . $e->getMessage()];
        }
    }

    private function updateArticleData()
    {
        try {
            $art = $this->fetchArticleData();
            $artdata = json_encode($art, JSON_UNESCAPED_UNICODE);
            $this->writeJson('www.json', $artdata);
            return ['status' => 'success', 'message' => '更新点点滴滴API数据成功'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => '更新点点滴滴json数据失败: ' . $e->getMessage()];
        }
    }

    private function updateImageData()
    {
        try {
            $arrimg = $this->fetchImageData();
            $arrxc = json_encode($arrimg, JSON_UNESCAPED_UNICODE);
            $this->writeJson('images.json', $arrxc);
            return ['status' => 'success', 'message' => '更新恋爱相册API数据成功'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => '更新恋爱相册json数据失败: ' . $e->getMessage()];
        }
    }

    private function updateEventData()
    {
        try {
            $listarr = $this->fetchEventData();
            $arrlist = json_encode($listarr, JSON_UNESCAPED_UNICODE);
            $this->writeJson('list.json', $arrlist);
            return ['status' => 'success', 'message' => '更新恋爱事件API数据成功'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => '更新恋爱事件json数据失败: ' . $e->getMessage()];
        }
    }
    
    private function fetchConfigData()
    {
        // 查询 diySet 表的配置数据
        $sqlDiy = "
        SELECT
            Blurkg,
            aiv_name_show,
            index_essay_num,
            index_leav_num,
            carousel,
            Fullscreen,
            StationMaintenanceSwitch,
            SmTitie,
            SmText,
            SmBgUrl,
            BackgroundMask,
            RandomSort
        FROM diySet
    ";

        $resultDiy = mysqli_query($this->connect, $sqlDiy);

        if (!$resultDiy) {
            error_log("Error executing query for diySet: " . mysqli_error($this->connect));
            throw new Exception("Error fetching DIY config data: " . mysqli_error($this->connect));
        }

        $diyConfigData = [];
        if (mysqli_num_rows($resultDiy)) {
            $diyConfigData = mysqli_fetch_array($resultDiy, MYSQLI_ASSOC);
            $diyConfigData['carousel'] = FormatCarouselDataWithUrlKey($diyConfigData['carousel']);
        }

        // 查询 wx_app_config 表的配置数据
        $sqlWxApp = "SELECT * FROM wx_app_config LIMIT 1";
        $resultWxApp = mysqli_query($this->connect, $sqlWxApp);

        if (!$resultWxApp) {
            error_log("Error executing query for wx_app_config: " . mysqli_error($this->connect));
            throw new Exception("Error fetching wx_app_config data: " . mysqli_error($this->connect));
        }

        $wxAppConfigData = [];
        if (mysqli_num_rows($resultWxApp)) {
            $wxAppConfigData = mysqli_fetch_array($resultWxApp, MYSQLI_ASSOC);
        }

        $configData = array_merge($diyConfigData, $wxAppConfigData);

        return $configData;
    }
    

    private function fetchLeavSetData()
    {

        $sql = "SELECT * FROM leavSet";
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            error_log("Error executing query: " . mysqli_error($this->connect));
            throw new Exception("Error: " . mysqli_error($this->connect));
        }
        $LeavSet = mysqli_fetch_assoc($result);
        
        // $LeavSet['randomWord'] = '';
    
        return $LeavSet;
    }



    private function fetchTextData()
    {
        $sql = "SELECT * FROM text";
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            error_log("Error executing query: " . mysqli_error($this->connect));
            throw new Exception("Error fetching text data: " . mysqli_error($this->connect));
        }
        $row = mysqli_fetch_assoc($result);

        $row['global_keywords'] = '';
        $row['site_description'] = '';

        return $row;
    }


    private function fetchLoveDayData()
    {
        $arrLoveTime = [];
        $sql = "SELECT * FROM loveday ORDER BY day_Weights DESC";
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            throw new Exception("Error fetching love day data: " . mysqli_error($this->connect));
        }
        while ($info_day = mysqli_fetch_array($result)) {
            $arrLoveTime[] = [
                "id" => intval($info_day['id']),
                "day_type" => intval($info_day['day_type']),
                "day_title" => $info_day['day_title'],
                "Loveday" => $info_day['day'],
                "FutureLoveday" => add_one_year($info_day['day']),
                "LovePast" => day_down($info_day['day'], date("Y-m-d"), false)[0],
                "LoveFuture" => day_down(date("Y-m-d"), $info_day['day'], true)[0]
            ];
        }
        return $arrLoveTime;
    }

    private function fetchArticleData()
    {
        $art = [];
        $sql = "SELECT * FROM article ORDER BY articletime DESC";
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            throw new Exception("Error fetching article data: " . mysqli_error($this->connect));
        }
        while ($info = mysqli_fetch_array($result)) {
            $articleText = $this->processArticleText($info['articletext']);
            if (intval($info['encryptionSwitch'])) {
                $info['articletitle'] = "标题已加密";
                $articleText = "内容已被加密";
            }

            $art[] = [
                "id" => $info['id'],
                "ipcity" => $info['ip_city'],
                "articletitle" => htmlspecialchars_decode($info['articletitle'], ENT_QUOTES),
                "articlename" => $info['articlename'],
                "articletime" => date('Y-m-d H:i', $info['articletime']),
                // "articletext" => nl2br($this->replaceAudioTags($articleText)),
                "articletext" => $this->replaceAudioTags($articleText),
                "arttext" => strip_tags($articleText),
                "encryptionSwitch" => intval($info['encryptionSwitch']),
            ];
        }
        return $art;
    }

    private function fetchImageData()
    {
        $arrimg = [];
        $sql = "SELECT * FROM loveImg ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_result($imgid, $imgtime, $imgtitle, $imgurl, $name, $img_code, $encryptionSwitch, $PasswordSee);
        $stmt->execute();
        while ($stmt->fetch()) {
            if (intval($encryptionSwitch)) {
                $imgurl = "https://loveli.kikiw.cn/Style/img/suo.svg";
                $imgtitle = "当前相册已被加密";
            }
            if($name == $this->LoversName['boy']){
                $thisAvatar = DetermineAvatarAddress(1);
            }else{
                $thisAvatar = DetermineAvatarAddress(0);
            }
            $arrimg[] = [
                "time" => $imgtime,
                "title" => $imgtitle,
                "imgurl" => $imgurl,
                "id" => $imgid,
                "name" => $name,
                'Avatar' => $thisAvatar,
                "encryptionSwitch" => intval($encryptionSwitch),
                "code" => $img_code
            ];
        }
        return $arrimg;
    }

    private function fetchEventData()
    {
        $listarr = [];
        $sql = "SELECT * FROM lovelist ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_result($listid, $icon, $listtitle, $listurl);
        $stmt->execute();
        while ($stmt->fetch()) {
            $Listurls = ExtractURL($listurl);
            $listurlObjects = [];
            foreach ($Listurls as $url) {
                $listurlObjects[] = ['url' => $url];
            }
            $listarr[] = [
                "icon" => $icon,
                "title" => $listtitle,
                "listurl" => $listurlObjects,
                "id" => $listid,
            ];
        }
        return $listarr;
    }

    private function writeJson($filename, $data)
    {
        if (file_put_contents($filename, $this->formatJson($data))) {
            return true;
        } else {
            throw new Exception("Error writing JSON to file: " . $filename);
        }
    }

    private function processArticleText($text)
    {
        // 处理文章文本中的HTML标签
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
        $boldg = "</text>";
        $sss = "<text class=\"shanchu\">";
        $xieg = "<text class=\"xie\">";

        // 替换标签
        $text = preg_replace("/<h1>/", $h1, $text);
        $text = preg_replace("/<h2>/", $h2, $text);
        $text = preg_replace("/<h3>/", $h3, $text);
        $text = preg_replace("/<h4>/", $h4, $text);
        $text = preg_replace("/<h5>/", $h5, $text);
        $text = preg_replace("/<h6>/", $h6, $text);
        $text = preg_replace("/<\/h1>|<\/h2>|<\/h3>|<\/h4>|<\/h5>|<\/h6>/", $jiewei, $text);
        return $text;
    }

    private function replaceAudioTags($html)
    {

        preg_match_all('/<audio[^>]*>.*?<\/audio>/s', $html, $matches);

        // 获取匹配到的所有 <audio> 标签
        $audioTags = $matches[0];

        foreach ($audioTags as $audioTag) {
            preg_match('/data-id="(.*?)"/', $audioTag, $dataIdMatch);
            $dataId = $dataIdMatch[1] ?? '';
            preg_match('/data-type="(.*?)"/', $audioTag, $dataTypeMatch);
            $dataType = $dataTypeMatch[1] ?? '';
            preg_match('/data-url="(.*?)"/', $audioTag, $DiyUrlMatch);
            $DiyUrl = $DiyUrlMatch[1] ?? '';

            // 获取音乐数据
            $musicData = getMusicInfo($dataType, "song", $dataId);

            // 解码 JSON 数据为关联数组
            $musicData = json_decode($musicData, true);

            // 如果有自定义 URL
            if ($DiyUrl) {
                $musicData['url'] = $DiyUrl;
            }

            // 构建替换后的 <audio> 标签
            $replacement = '<audio author="' . htmlspecialchars($musicData['author'], ENT_QUOTES) . '" name="' . htmlspecialchars($musicData['name'], ENT_QUOTES) . '" poster="' . htmlspecialchars($musicData['cover'], ENT_QUOTES) . '" src="' . htmlspecialchars($musicData['url'], ENT_QUOTES) . '" id="myAudio" controls loop bindplay="funPlay" bindpause="funPause"></audio>';

            // 将当前 <audio> 标签替换为新标签
            $html = str_replace($audioTag, $replacement, $html);
        }

        return $html;
    }



    private function formatJson($json, $html = false)
    {
        // 格式化JSON
        $tabcount = 0;
        $result = '';
        $inquote = false;
        $ignorenext = false;
        $tab = $html ? "&nbsp;&nbsp;&nbsp;" : "\t";
        $newline = $html ? "<br/>" : "\n\n";

        for ($i = 0; $i < strlen($json); $i++) {
            $char = $json[$i];
            if ($ignorenext) {
                $result .= $char;
                $ignorenext = false;
            } else {
                switch ($char) {
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
}


$LoversName = [
    'boy' => $text['boy'],
    'girl' => $text['girl'],
];

$api = new APIDataGenerator($connect, $conn, $LoversName);
$api->generateData();
