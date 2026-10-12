<?php
session_start();
include_once 'Nav.php';
require_once __DIR__ . '/VisitStats.php';

$Behavior_sql = "select * from Behavior order by id desc limit 6";
$resBehavior = mysqli_query($connect, $Behavior_sql);
$visitStats = lg_visit_dashboard_stats($connect);
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

<div class="lg-dash-head">
    <div>
        <h4>后台首页</h4>
        <p>内容概览与最近登录，点击卡片可进入对应管理页</p>
    </div>
    <a href="/admin/Set.php" class="btn btn-sm btn-primary">基本设置</a>
</div>

<div class="row">
    <div class="col-md-6 col-xl-3">
        <a href="/admin/leavSet.php" class="card lg-stat-card mb-3">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card4.png" alt="留言数量">
                </div>
                <p class="label">留言数量</p>
                <p class="value"><?php echo (int) $shu; ?><i>条</i></p>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="/admin/littleSet.php" class="card lg-stat-card mb-3">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card1.png" alt="点点滴滴">
                </div>
                <p class="label">点点滴滴</p>
                <p class="value"><?php echo (int) $diannub; ?><i>条</i></p>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="/admin/lovelist.php" class="card lg-stat-card mb-3">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card3.png" alt="恋爱清单">
                </div>
                <p class="label">恋爱清单</p>
                <p class="value"><?php echo (int) $listnub; ?><i>条</i></p>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="/admin/loveImgSet.php" class="card lg-stat-card mb-3">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card2.png" alt="相册数量">
                </div>
                <p class="label">相册数量</p>
                <p class="value"><?php echo (int) $imgnub; ?><i>个</i></p>
            </div>
        </a>
    </div>
</div>

<div class="row lg-visit-strip">
    <div class="col-md-4">
        <a href="/admin/VisitLog.php" class="card lg-stat-card mb-3">
            <div class="card-body">
                <p class="label">今日访问</p>
                <p class="value"><?php echo (int) $visitStats['today']; ?><i>人</i></p>
                <p class="lg-metric-hint">浏览 <?php echo (int) $visitStats['today_pv']; ?> 次</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/VisitLog.php" class="card lg-stat-card mb-3">
            <div class="card-body">
                <p class="label">昨日访问</p>
                <p class="value"><?php echo (int) $visitStats['yesterday']; ?><i>人</i></p>
                <p class="lg-metric-hint">浏览 <?php echo (int) $visitStats['yesterday_pv']; ?> 次</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/VisitLog.php" class="card lg-stat-card mb-3">
            <div class="card-body">
                <p class="label">累计访问</p>
                <p class="value"><?php echo (int) $visitStats['total']; ?><i>人</i></p>
                <p class="lg-metric-hint">浏览 <?php echo (int) $visitStats['total_pv']; ?> 次</p>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="lg-panel-head">
                    <h4 class="header-title size_18">最近登录</h4>
                    <a href="/admin/LoginInfo.php" class="btn btn-sm btn-outline-primary">查看全部</a>
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
                            $hasBehavior = false;
                            while ($info = mysqli_fetch_array($resBehavior)) {
                                $hasBehavior = true;
                            ?>
                                <tr>
                                    <td>
                                        <h5 class="mb-0">
                                            <span class="badge badge-success-lighten">
                                                <i class="mdi mdi-account-circle mr-1 rihjt-0"></i>
                                                <?php echo htmlspecialchars((string) $info['L_name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </h5>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars(date('Y-m-d H:i:s', (int) $info['L_date']), ENT_QUOTES, 'UTF-8'); ?>
                                            <div class="color">
                                                <?php echo htmlspecialchars(time_tran($info['L_date']) . '登录', ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                        </small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars((string) $info['L_city'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td>
                                        <button type="button" style="margin-top: 0.2rem;white-space: nowrap;"
                                            class="btn btn-outline-dark btn-rounded btn-sm">
                                            <?php echo GetOsIcon($info['L_os']) . htmlspecialchars((string) $info['L_os'], ENT_QUOTES, 'UTF-8') . ' ｜ ' . GetBrIcon($info['L_br']) . htmlspecialchars((string) $info['L_br'], ENT_QUOTES, 'UTF-8'); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            if (!$hasBehavior) {
                                echo '<tr><td colspan="4" class="lg-empty">暂无登录记录</td></tr>';
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
