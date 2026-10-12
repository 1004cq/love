<?php
use Metowolf\Meting;
header('Access-Control-Allow-Origin:*');//允许所有来源访问
header('Access-Control-Allow-Method:POST,GET');//允许访问的方式
require_once 'Meting.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['id']) && !empty($_GET['type']) && !empty($_GET['media'])) {
        $id = $_GET['id'];
        $type = $_GET['type'];
        $media = $_GET['media'];
        echo getMusicInfo($media, $type, $id);
    }
}

function getMusicInfo($media = 'netease', $type = 'song', $id = '')
{
    $cookie = 'NMTID=00O0eWdyHv_rWCi-UlMq6e4DTYpIcUAAAGPVvz7Gw; JSESSIONID-WYYY=K8WNeZfTb84RwwzlsDqK7xKiEfVKSeoGeZ9SsRSJMCymjVlXIEEu8htvjCVUQ7vSi52tXoUT4dAX9z6jFutksKhH4RwTYSpHwV%2BZEK4u0P7yGF%2BP2KFw%2B8yWGOdbvNTVFTTGWn8eOgcpKsjC%2Bczmzp6ZCX%2FUil1xeF3VNpanYfzoOBoX%3A1716264091723; _iuqxldmzr_=32; _ntes_nnid=d92dd3eb0e2ca7345db7b100412fcca1,1716262291749; _ntes_nuid=d92dd3eb0e2ca7345db7b100412fcca1; WM_NI=m9FbZzBKbVZEbqIA%2FPLkMfVJeoeiKOoS8yn0jKDZa2w4XeiOmUCQhpbuszh0vd%2FaZDcUxIaaIZmrcQ4c7eBSjIqgG48BfnHV0zTYvBH1b3%2B4BJgt%2BK%2BpjtnRHPXhelbTZHc%3D; WM_NIKE=9ca17ae2e6ffcda170e2e6ee84fc39fcbe99b2e96e928e8bb7d85b968e9bb0c825b8bb99b8f04baabfaaccb22af0fea7c3b92aafe78a83d9728295aeb7d94fa2af87a2ae5c90b58387d542a7b1ab8aca45ad8df89aed3caa958cbbe43ab5ec9886ec45af8b8db2eb7ca2abbd9beb3cb6bce182d950a18bb8aae46b8b86a393e25bf8bdf986c159aa97bf88c640fc9eaa8fcb5ef4a69cbaf54a8cefaed4aa408296a995aa41e9b0a695ed5a8c909889e868a28c96a9e237e2a3; WM_TID=SXAL78wkcc1BARVEEEPVupbXSzE2be%2Fj; __snaker__id=mD3jjZf5IRhJZoRx; gdxidpyhxdE=A3qS%5CU0dy8H%2BSsNCBMuJArM6rs2svtkGkSupB6BHDMHJtA9OE8vuGx8pL3jPoEvVSibpOWSIjtM017QN51AtKp39EY8ioMOdXf%5C56CvXhrUTbbLbjrTBpNfE0b7sn9EVwYnPgpX%2BG5x2HJ09MuQIOHwThH0Gb5NGo%2FrV4QQiZT%2BG9QMt%3A1716263196504; sDeviceId=YD-Jg0J%2BIbFPlhEElEREUbR7paWGjF5EJue; __csrf=4820d9161db3f297d9388b4ffad1fff9; MUSIC_U=009DE36B12E650F3BC68C22904800CF8ACA24286AFE2F7BD1CF187F8321BC1C455E6F3424654A3B92D871425A984663DF8C318629DE3EF570AAAED263193D0645974C6FA07ED1C388C67ED4CDD40D8844F28A7C975420D53B855C342268406E480C4DC4B5D6D44951B1D48697A70AB13CA44D2B723116D90FE5E53BB51B2178DABF44D032D9A845C09915CBAC683FE901289D49F52A91A7A1ADEF2FFCD77E703DC451EDD3304ED4A3C00A9E6F9631FC94F56EAF4419E38AAB5FF6F7420FF7AB61F861CA08100FDB4258D6308826C68587FB2071C03E53CAAC13A79B6B8C6DCD1619B133D4B40C31D3DE6DE5718C097620BF2D46E7F431F8DE013CF9276011BB1A6033AA9C0F1A79C39640DA0A0A745F20CD0A2264B6760137D1DEFE4A23AD903D85F9561915AADC86E1635FA32923D62028AD5829A57C82AB5D0F115704718205C5ABB0EFAD8F28FB020C1E784345C29E45D883ED370ADA1218BD90B7C677CAE3F; ntes_utid=tid._.1aH8iI1osBhFQ0VRUEeEvoKDGiU8A3Wk._.0; ntes_kaola_ad=1';
    $api = new Meting($media);
    if ($media == 'netease') {
        $api->cookie($cookie);
    }
    $info = array();
    switch ($type) {
        case 'song':
            $datas = $api->format(true)->song($id);
            $datas = json_decode($datas, true);
            $data = $datas[0];
            $cover = json_decode($api->format(true)->pic($data['pic_id']), true)['url'];
            $url = json_decode($api->format(true)->url($data['id']), true)['url'];
            /**
             * 修复网易云音乐防止盗链
             */
            if ($media == 'netease') {
                $url = str_replace("http://m7c", "http://m7", $url);
                $url = str_replace("http://m8c", "http://m8", $url);
            }
            if ($media == "tencent") {
                $url = str_ireplace("ws.stream.qqmusic.qq.com", "dl.stream.qqmusic.qq.com", $url);
            }
            $url = str_replace("http://", "https://", $url);
            $artists = '';
            foreach ($data['artist'] as $artist) {
                $artists .= $artist . ' ';
            }
            $info = array(
                'name' => $data['name'],
                'url' => $url,
                'song_id' => $data['id'],
                'cover' => $cover,
                'author' => rtrim($artists)
            );
            break;
        case 'collect':
            $datas = $api->format(true)->playlist($id);
            $datas = json_decode($datas, true);
           // var_dump($datas);
            foreach ($datas as $keys => $data) {
                $cover = json_decode($api->format(true)->pic($data['pic_id']), true)['url'];
                $info[$keys] = array(
                    'name' => $data['name'],
                    'url' => '',
                    'song_id' => $data['id'],
                    'cover' => $cover,
                    'author' => $data['artist'][0]
                );
            }
            //shuffle($info);随机音乐TODO
            break;
        default:
            $data = "";
            break;
    }
    return json_encode($info, true);
}
