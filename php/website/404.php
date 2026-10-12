<!DOCTYPE html>
<html lang="zh-CN">

<?php
include_once 'admin/connect.php';
include_once 'admin/Function.php';

if (isset($AccessAddress) && !empty($AccessAddress)) {
    $ipy = $ip_if;
    $threshold = 10;

    // 查询是否超过阈值
    $check_ip_sql = "SELECT COUNT(*) as access_count FROM warning WHERE ip = ?";
    $stmt = $conn->prepare($check_ip_sql);
    $stmt->bind_param("s", $ipy);
    $stmt->execute();
    $stmt->bind_result($access_count);
    $stmt->fetch();
    $stmt->close();


    if ($access_count >= $threshold) {
        // 查询当前ip是否存在数据库
        $check_iperror_sql = "SELECT COUNT(*) as error_count FROM IPerror WHERE State = ?";
        $stmt = $conn->prepare($check_iperror_sql);
        $stmt->bind_param("s", $ipy);
        $stmt->execute();
        $stmt->bind_result($error_count);
        $stmt->fetch();
        $stmt->close();

        if ($error_count == 0) {
            // 如未封禁 则插入数据库中
            $insert_ip_sql = "INSERT INTO IPerror (ipAdd, Time, State, text) VALUES (?, NOW(), ?, ?)";
            $conn->set_charset('utf8mb4');
            $stmt = $conn->prepare($insert_ip_sql);

            $ip_address = $ip_if;
            @$ErrMsg  = "非法访问次数超过限制 系统自动封禁";
            $IpCity = Get_City($ip_address);
            $stmt->bind_param("sss", $IpCity, $ip_address, $ErrMsg);

            if (!$stmt->execute()) {
                echo "错误信息：" . $stmt->error;
            }
            $stmt->close();
        }else{
            header("Location: ../index.php");
            exit();
        }
    } else {
        // 未封禁继续访问则添加到数据库中记录
        $ipcharu = "INSERT INTO warning (ip, gsd, time, file) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($ipcharu);
        $stmt->bind_param("ssss", $ipy, $gsd, $time, $file);
        $ipy = $ip_if;
        $gsd = Get_City($ipy);
        $time = gmdate("Y-m-d H:i:s", time() + 8 * 3600);
        $file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

        if (!$stmt->execute()) {
            echo "错误信息：" . $stmt->error;
        }

        $stmt->close();
        @$ErrMsg = '页面访问错误或丢失 你的行为已记录！';
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/css/errorPage.css">

    <title>404 Not Found — <?php echo $text['title'] ? $text['title'] : '404' ?></title>
</head>

<body>
    <header class="header">
        <nav class="nav container">
            <a href="#" class="nav__logo">
                <?php echo $text['title'] ? $text['title'] : '404' ?>
            </a>
        </nav>
    </header>

    <main class="main">
        <section class="home">
            <div class="home__container container">
                <div class="home__data <?php echo @$PageType ? 'cardList' : '' ?>">
                    <?php if (@$PageType): ?>
                        <span class="home__subtitle">抱歉 你的IP已被系统封禁 请注意非法行为！ 如需解封请联系小站管理员</span>
                        <ul class="illegalContent">
                            <li class="warningItem"><b>封禁IP：</b> <?php echo $testingIP ?></li>
                            <li class="warningItem"><b>IP归属地：</b> <?php echo $testingCity ?></li>
                            <li class="warningItem"><b>封禁时间：</b><?php echo $testingTime ?></li>
                            <li class="warningItem"><b>封禁备注：</b><?php echo $testingText ?></li>
                        </ul>
                        <p class="home__description">
                        </p>

                        <a href="../index.php" class="home__button">
                            返回首页
                        </a>
                    <?php else: ?>
                        <span class="home__subtitle"><?php echo @$ErrMsg ? @$ErrMsg : '抱歉 页面未找到！请检查路径是否正确' ?></span>
                        <h1 class="home__title">Error <b class="Text404">404</b></h1>
                        <p class="home__description">
                        </p>
                        <a href="../index.php" class="home__button">
                            返回首页
                        </a>
                    <?php endif; ?>

                </div>

                <div class="home__img">
                    <img src="../Style/img/ghost-img.png" alt="">
                    <div class="home__shadow"></div>
                </div>
            </div>

            <footer class="home__footer">
                <a href="../index.php">
                    <span>Copyright © <?php echo $text['Copyright'] ?> All Rights Reserved.</span>
                </a>
            </footer>
        </section>
    </main>
    <script src="../Style/js/scrollreveal.min.js"></script>
    <script src="../Style/js/errorPageMain.js"></script>
</body>

</html>

<?php
exit;
?>