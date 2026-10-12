<!--
/*
 *                        _oo0oo_
 *                       o8888888o
 *                       88" . "88
 *                       (| -_- |)
 *                       0\  =  /0
 *                     ___/`---'\___
 *                   .' \\|     |// '.
 *                  / \\|||  :  |||// \
 *                 / _||||| -:- |||||- \
 *                |   | \\\  - /// |   |
 *                | \_|  ''\---/''  |_/ |
 *                \  .-\__  '-'  ___/-. /
 *              ___'. .'  /--.--\  `. .'___
 *           ."" '<  `.___\_<|>_/___.' >' "".
 *          | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *          \  \ `_.   \_ __\ /__ _/   .-` /  /
 *      =====`-.____`.___ \_____/___.-`___.-'=====
 *                        `=---='
 * 
 * 
 *      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 *            佛祖保佑     永不宕机     永无BUG
 * 
 *        佛曰:  
 *                写字楼里写字间，写字间里程序员；  
 *                程序人员写程序，又拿程序换酒钱。  
 *                酒醒只在网上坐，酒醉还来网下眠；  
 *                酒醉酒醒日复日，网上网下年复年。  
 *                但愿老死电脑间，不愿鞠躬老板前；  
 *                奔驰宝马贵者趣，公交自行程序员。  
 *                别人笑我忒疯癫，我笑自己命太贱；  
 *                不见满街漂亮妹，哪个归得程序员？
 */

 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditors: Ki.
 * @LastEditTime: 2023-03-24 16:36:06
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2024 by Ki All Rights Reserved.
 * Document https://blog.kikiw.cn/index.php/archives/65/
 * Warning 当前版本为付费版 禁止出售倒卖 一旦发现 将停止后期维护与更新 永久拉入黑名单
 * 开发需要投入许多时间 请理解 开发不易 感谢您的购买 祝您使用愉快
-->


<?php
if (!defined('ConnDie')) {
    $ErrMsg = '你当前无权访问本页 请重试！';
    include_once '../404.php';
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title>数据库连接失败</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="Ki" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">

    <!-- App css -->
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="/Style/css/loading.css" rel="stylesheet">
</head>



<style>
    body {
        font-family: 'Noto Serif SC', serif;
    }

    .card {
        border-radius: 15px;
    }

    span.btn {
        padding: .2rem 1rem;
    }

    @media (min-width: 992px) {
        .col-lg-5 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 41.66667%;
            flex: 0 0 700px;
            max-width: 700px;
        }
    }

    .card-header.pt-4.pb-4.text-center.bg-primary {
        border-radius: 15px 15px 0 0;
    }

    .bg-primary {
        background-color: #e91e63 !important;
    }

    .btn-primary {
        border-radius: 20px;
    }

    .info {
        margin-bottom: 20px;
        font-size: 1rem;
        line-height: 2em;
    }

    .mar_top {
        margin-top: 20px;
    }

    .btn-success {
        border-radius: 20px;
    }

    .file_url {
        border-radius: 10px;
        margin: 0 10px;
    }

    .titleLG {
        color: #fff;
    }

    .footerText {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px dashed #d7d3d3;
    }

    .errIcon {
        width: 100%;
        text-align: center;
    }

    .errIcon img{
        width: 50%;
    }
</style>




<body class="authentication-bg">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <h3 class="text-center mt-0 font-weight-bold titleLG">LG_NewUI </h3>
                            <h3 class="titleLG">数据库连接失败</h3>
                        </div>

                        <div class="card-body p-4" style="padding-top: 0rem !important;">
                            <div class="errIcon">
                                <img src="../Style/img/Conn.png" alt="">
                            </div>
                            <div class="text-center w-75 m-auto">
                                <div class="info">请检查数据库信息是否配置正确<i style="color: #ff9b9b;"></i></div>
                            </div>

                            <div class="text-center info">如需重新安装请到<span
                                    class="btn btn-outline-warning file_url">install</span>目录手动删除<span
                                    class="btn btn-outline-success file_url">install.lock</span>安装锁文件<i
                                    style="color: #ff9b9b;"></i></div>
                            <div class="footerText form-group mb-0 text-center">
                                <a href="https://lguiy.kikiw.cn/wiki/#/">

                                    <button class="btn btn-outline-primary btn-rounded" type="submit"> 程序使用文档
                                    </button>
                                </a>
                                <a href="../index.php">
                                    <button class="btn btn-dark btn-rounded" type="submit"> 跳转小站首页
                                    </button>
                                </a>
                            </div>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
    </div>
    <!-- end page -->



    <footer class="footer footer-alt">
        Copyright © <?php echo date("Y") ?> Ki. && <a href="https://blog.kikiw.cn/index.php/archives/65/"
            target="_blank">LG_NewUi</a> All
        Rights Reserved.
    </footer>
</body>

</html>