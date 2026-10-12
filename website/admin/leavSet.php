<?php
session_start();
include_once 'Nav.php';
$leav_code = $_GET['code'];
if ($leav_code == "switch") {
    $liuyan = "SELECT * FROM leaving where switch = 0 order by id desc";
} else {
    $liuyan = "SELECT * FROM leaving order by id desc";
}
$stmt = $conn->prepare($liuyan);
$stmt->bind_result($id, $name, $qq, $text, $time, $ip, $city, $switch, $OS_info, $BR_info);
$result = $stmt->execute();
if (!$result)
    echo "错误信息：" . $stmt->error;



$leavSet = "select * from leavSet";
$Set = mysqli_query($connect, $leavSet);
$Setinfo = mysqli_fetch_array($Set);
$anonymousArr = $Setinfo['anonymousArr'];
$anonymousArr = explode("\n", $anonymousArr);
$anonymousList = array();
foreach ($anonymousArr as $array_L) {
    array_push($anonymousList, $array_L);
}
?>


<style>
    .text_ov {
        width: 13em;
        overflow: hidden;
    }

    .text_ov img {
        width: 1.3rem;
    }

    .text_ov button {
        width: 100%;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .mdi-checkbox-marked-circle {
        color: #53ba8c;
        font-size: 1rem;
    }

    .mdi-close-circle {
        color: #ff5d79;
        font-size: 1rem;
    }

    .text-lg-center {
        text-align: center !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="text-lg-right">
                            <a class="fabu" href="/admin/leavP.php">
                                <button type="button" class="btn btn-secondary mb-2 mr-2"
                                    style="border-radius: 0.5rem"><i class=" mdi mdi-brightness-5"></i> 留言相关设置
                                </button>
                            </a>
                        </div>
                    </div><!-- end col-->
                </div>
                <h4 class="header-title mb-3 size_18">留言管理
                    <button type="button" class="btn btn-success btn-sm btn-rounded margin_left">
                        共<b>
                            <?php echo $leav['shu'] ?>
                        </b>条
                    </button>
                </h4>

                <?php if ($shu <= 0): ?>
                    <div class="NoDataCard-warp">
                        <div class="NoDataCard">
                            <img src="../Style/img/NoData.svg" alt="">
                        </div>
                        <div class="NoDataInfo">
                            <h3>当前页面暂无数据！</h3>
                        </div>
                    </div>
                <?php endif ?>
                <div class="<?php echo ($shu <= 0) ? ' HiddenElements' : '' ?>">
                    <div class="text-lg-center">
                        <?php if (@$leav_code == "switch") { ?>
                            <a class="fabu" href="/admin/leavSet.php">
                                <button type="button" class="btn btn-info mb-2 mr-2">
                                    <i class="mdi mdi-cached"></i>查看全部留言
                                </button>
                            </a>
                        <?php } else { ?>
                            <a class="fabu" href="/admin/leavSet.php?code=switch">
                                <button type="button" class="btn btn-danger mb-2 mr-2">
                                    <i class="mdi mdi-cached"></i>未审核留言
                                </button>
                            </a>
                        <?php } ?>

                    </div>
                    <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>留言内容</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>QQ</th>
                                <th>IP/归属地</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>

                        <form class="needs-validation" action="littleupda.php" method="post">
                            <tbody>

                                <?php
                                while ($stmt->fetch()) {
                                    $MessageRes["id"] = $id;
                                    $MessageRes["name"] = $name;
                                    $MessageRes["text"] = $text;
                                    $MessageRes["time"] = $time;
                                    $MessageRes["qq"] = $qq;
                                    $MessageRes["switch"] = $switch;
                                    $MessageRes["ip"] = $ip;
                                    $MessageRes["city"] = $city;
                                    $LookText = str_replace(array("\r", "\n"), '\\n', $text);
                                ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                                <i class="ri-at-line rihjt-0"></i>
                                                <?php echo getNextSequentialNumber(); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="text_ov">
                                                <button type="button" onclick="PublicPopUpLayer(2, '', '<?php echo $LookText;  ?>')" class="btn btn-outline-primary btn-rounded"><?php echo str_replace(['"'], ["'"], parseEmojis($text)) ?></button>
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                <small class="text-muted">
                                                    <?php echo date('Y-m-d H:i:s', $time) ?>
                                                    <div class="color">
                                                        <?php echo time_tran($time) ?>
                                                        <?php if ($switch) { ?>
                                                            <i class="mdi mdi-checkbox-marked-circle mr-1"></i>
                                                        <?php } else { ?>
                                                            <i class="mdi mdi-close-circle mr-1"></i>
                                                        <?php } ?>
                                                    </div>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <h5><span class="badge badge-success-lighten"><i
                                                        class="mdi mdi-account-circle mr-1 rihjt-0"></i>
                                                    <?php echo $name ?>
                                                </span>
                                            </h5>
                                        </td>
                                        <td>
                                            <div style="text-align: center;" class="MegAvatar">
                                                <?php if ($qq == "anon"): ?>
                                                    <img class="lazy"
                                                        style="width: 3rem;border-radius: 10rem;border: 1px solid #ddddddb8;"
                                                        data-src="<?php echo $anonymousList[array_rand($anonymousList)] ?>" alt="">
                                                <?php endif; ?>

                                                <?php if ($qq <> "anon"): ?>
                                                    <img class="lazy"
                                                        style="width: 3rem;border-radius: 10rem;border: 1px solid #ddddddb8;"
                                                        data-src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $qq ?>&s=100" alt="">
                                                    <div style="margin-top:0.5rem"><?php echo $qq ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>
                                                <span class="badge badge-danger-lighten"
                                                    onclick="PublicPopUpLayer(5,1,'<?php echo htmlspecialchars(json_encode($MessageRes, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
                                                    <?php if ($ip) {
                                                        echo $ip;
                                                    } else {
                                                        echo "127.0.0.1";
                                                    } ?>
                                                </span>
                                                <span class="badge badge-warning-lighten">
                                                    <?php if ($city) {
                                                        echo $city;
                                                    } else {
                                                        echo "未知";
                                                    } ?>
                                                </span>
                                            </h5>
                                            <button type="button" style="margin-top: 0.2rem;"
                                                class="btn btn-outline-dark btn-rounded btn-sm">
                                                <?php echo GetOsIcon($OS_info) . $OS_info . " ｜ " . GetBrIcon($BR_info) . $BR_info ?>
                                            </button>

                                        </td>
                                        <td style="text-align: center;">
                                            <div class="dropdown">
                                                <a style="margin-bottom: 0.5rem;"
                                                    class="btn btn-secondary dropdown-toggle btn-rounded" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="mdi mdi-circle-edit-outline mr-1"></i>审核
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <?php if (!$switch) { ?>
                                                        <a class="dropdown-item" href="javascript:adopt(<?php echo $id; ?>);">
                                                            <i class="mdi mdi-checkbox-marked-circle mr-1"></i>通过</a>
                                                    <?php } else { ?>
                                                        <a class="dropdown-item" href="javascript:fail(<?php echo $id; ?>);">
                                                            <i class="mdi mdi-close-circle mr-1"></i>驳回</a>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div class="userProcessing">
                                                <a href="editMessage.php?id=<?php echo $id ?>" class="mr-1">
                                                    <button style="white-space: nowrap;" type="button"
                                                        class="btn btn-outline-dark btn-rounded">
                                                        <i class="mdi mdi-calendar-edit mr-1"></i>编辑
                                                    </button>
                                                </a>
                                                <a
                                                    onclick="PublicPopUpLayer(1,'leaving','<?php echo htmlspecialchars(json_encode($MessageRes, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
                                                    <button style="white-space: nowrap;" type="button"
                                                        class="btn btn-danger btn-rounded">
                                                        <i class="mdi mdi-delete-empty mr-1"></i>删除
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>

                        </form>
                        </tbody>
                    </table>
                </div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>



<style>
    .table td,
    .table th {
        white-space: nowrap;
    }
</style>
<script>
    function del(id, text) {
        if (confirm('您确认要删除 ' + text + ' 内容吗')) {
            location.href = 'delleav.php?id=' + id + '&text' + text;
        }
    }
</script>

<?php
include_once 'Footer.php';
?>

</body>

</html>