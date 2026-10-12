<?php
session_start();
include_once 'Nav.php';
require_once __DIR__ . '/VisitStats.php';

$Behavior_sql = "select * from Behavior order by id desc limit 6";
$resBehavior = mysqli_query($connect, $Behavior_sql);
$visitStats = lg_visit_dashboard_stats($connect);
$recentVisits = lg_visit_recent($connect, 12);
?>
<div class="center_alert" style="text-align: center">
    <?php if ($login['user'] == $default_info['admin']) { ?>
        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert"
            style="display: inline-block;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Warning - </strong> 当前账号为默认账号 请尽快修改！
        </div>
    <?php } ?>
    <?php if (password_verify($default_info['pw'], $login['pw'])) { ?>
        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert"
            style="display: inline-block;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Warning - </strong> 当前密码为默认密码 请尽快修改！
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-md-4 col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted font-weight-normal mt-0 text-truncate">今日访问人数</h5>
                <h3 class="my-2 py-1"><?php echo (int) $visitStats['today']; ?><i>人</i></h3>
                <p class="mb-0 text-muted"><small>今日浏览 <?php echo (int) $visitStats['today_pv']; ?> 次</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted font-weight-normal mt-0 text-truncate">昨日访问人数</h5>
                <h3 class="my-2 py-1"><?php echo (int) $visitStats['yesterday']; ?><i>人</i></h3>
                <p class="mb-0 text-muted"><small>昨日浏览 <?php echo (int) $visitStats['yesterday_pv']; ?> 次</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted font-weight-normal mt-0 text-truncate">总访问人数</h5>
                <h3 class="my-2 py-1"><?php echo (int) $visitStats['total']; ?><i>人</i></h3>
                <p class="mb-0 text-muted"><small>累计浏览 <?php echo (int) $visitStats['total_pv']; ?> 次</small></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-0 size_18">最近访问（IP / 国家 / 省市）</h4>
                    <a href="/admin/VisitLog.php" class="btn btn-sm btn-outline-primary">查看全部</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>时间</th>
                                <th>访问 IP</th>
                                <th>国家</th>
                                <th>省份</th>
                                <th>城市</th>
                                <th>页面</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$recentVisits): ?>
                                <tr>
                                    <td colspan="6" class="text-muted">暂无访问记录（访客打开前台页面后会自动统计）</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($recentVisits as $visit): ?>
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars(date('Y-m-d H:i:s', (int) $visit['visit_time']), ENT_QUOTES, 'UTF-8'); ?>
                                        </small>
                                    </td>
                                    <td><code><?php echo htmlspecialchars((string) $visit['ip'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                                    <td><?php echo htmlspecialchars((string) ($visit['country'] ?: '未知'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars((string) ($visit['province'] ?: '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars((string) ($visit['city'] ?: '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-truncate" style="max-width: 220px;">
                                        <?php echo htmlspecialchars((string) $visit['page'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card4.png" alt="留言数量">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate">留言数量</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $shu ?><i>条</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card1.png" alt="点点滴滴">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="New Leads">点点滴滴</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $diannub ?><i>条</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card3.png" alt="恋爱清单">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Deals">恋爱清单</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $listnub ?><i>条</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card2.png" alt="相册数量">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Campaign Sent">相册数量</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $imgnub ?><i>个</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

</div>
<!-- end row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-4">
                        <div class="text-lg-right">
                            <button type="button" class="btn btn-danger mb-2 mr-2"><i class="mdi mdi-basket mr-1"></i>
                                登录行为
                            </button>
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>管理员昵称</th>
                                <th>登录时间</th>
                                <th style="white-space: nowrap;">登录地址</th>
                                <th style="white-space: nowrap;">设备/浏览器</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($info = mysqli_fetch_array($resBehavior)) {
                            ?>
                                <tr>
                                    <td>
                                        <h5><span class="badge badge-success-lighten"><i
                                                    class="mdi mdi-account-circle mr-1 rihjt-0"></i>
                                                <?php echo $info['L_name'] ?>
                                            </span>
                                        </h5>

                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('Y-m-d H:i:s', $info['L_date']) ?>
                                            <div class="color">
                                                <?php echo time_tran($info['L_date']) . "登录" ?>
                                            </div>
                                        </small>
                                    </td>
                                    <td>
                                        <?php echo $info['L_city'] ?>
                                    </td>
                                    <td>
                                        <button type="button" style="margin-top: 0.2rem;white-space: nowrap;"
                                            class="btn btn-outline-dark btn-rounded btn-sm">
                                            <?php echo GetOsIcon($info['L_os']) . $info['L_os'] . " ｜ " . GetBrIcon($info['L_br']) . $info['L_br'] ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include_once 'Footer.php';
?>

</body>

</html>