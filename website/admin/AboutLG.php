<!--
 * @Author: Ki.
 * @Description: 本页面对前端无任何影响 建议用户保留 
 * @Description: 如更改后则无法获取最新版本内容等信息 请自行考虑
 * Copyright (c) 2022-2024 by Ki All Rights Reserved. 
-->
<?php
session_start();
include_once 'Nav.php';
$url = 'https://www.kikiw.cn/Love/LGNewUI.php';
$lines_array = file($url);
$lines_string = implode('', $lines_array);
?>

<style>
    .btn-success {
        border-radius: 10px;
        border: 2px solid #fff;
    }

    .btn-success:hover {
        background-color: transparent;
        border-color: #fff;
        box-shadow: none;
    }
</style>

<div class="row">

    <div class="col-md-12">
        <?php echo ($lines_string); ?>
    </div>

</div>

<?php
include_once 'Footer.php';
?>

</body>

</html>