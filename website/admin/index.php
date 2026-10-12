<?php
session_start();
include_once 'Nav.php';
require_once __DIR__ . '/VisitStats.php';

$Behavior_sql = "select * from Behavior order by id desc limit 6";
$resBehavior = mysqli_query($connect, $Behavior_sql);
$visitStats = lg_visit_dashboard_stats($connect);
$recentVisits = lg_visit_recent($connect, 8);
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

<div class="lg-admin-page-head">
    <div>
        <h2>仪表盘</h2>
        <p>访问概况、内容数据与最近登录行为</p>
    </div>
    <div>
        <a href="/admin/VisitLog.php" class="btn btn-sm btn-outline-primary">访问统计</a>
        <a href="/admin/system_status.php" class="btn btn-sm btn-outline-dark">系统状态</a>
    </div>
</div>

<div class="lg-metric-grid">
    <div class="lg-metric">
        <p class="label">今日访问人数</p>
        <div class="value"><?php echo (int) $visitStats['today']; ?><i>人</i></div>
        <p class="hint">今日浏览 <?php echo (int) $visitStats['today_pv']; ?> 次</p>
    </div>
    <div class="lg-metric is-yesterday">
        <p class="label">昨日访问人数</p>
        <div class="value"><?php echo (int) $visitStats['yesterday']; ?><i>人</i></div>
        <p class="hint">昨日浏览 <?php echo (int) $visitStats['yesterday_pv']; ?> 次</p>
    </div>
    <div class="lg-metric is-total">
        <p class="label">总访问人数</p>
        <div class="value"><?php echo (int) $visitStats['total']; ?><i>人</i></div>
        <p class="hint">累计浏览 <?php echo (int) $visitStats['total_pv']; ?> 次</p>
    </div>
</div>

<div class="lg-admin-section">
    <div class="lg-admin-section-title">
        <h4>内容数据</h4>
    </div>
    <div class="lg-stat-grid">
        <div class="lg-stat">
            <div class="lg-stat-icon">
                <img draggable="false" src="../Style/img/AdminIcon/card4.png" alt="">
            </div>
            <div class="meta">
                <p class="label">留言数量</p>
                <div class="value"><?php echo (int) $shu; ?><i>条</i></div>
            </div>
        </div>
        <div class="lg-stat">
            <div class="lg-stat-icon">
                <img draggable="false" src="../Style/img/AdminIcon/card1.png" alt="">
            </div>
            <div class="meta">
                <p class="label">点点滴滴</p>
                <div class="value"><?php echo (int) $diannub; ?><i>条</i></div>
            </div>
        </div>
        <div class="lg-stat">
            <div class="lg-stat-icon">
                <img draggable="false" src="../Style/img/AdminIcon/card3.png" alt="">
            </div>
            <div class="meta">
                <p class="label">恋爱清单</p>
                <div class="value"><?php echo (int) $listnub; ?><i>条</i></div>
            </div>
        </div>
        <div class="lg-stat">
            <div class="lg-stat-icon">
                <img draggable="false" src="../Style/img/AdminIcon/card2.png" alt="">
            </div>
            <div class="meta">
                <p class="label">相册数量</p>
                <div class="value"><?php echo (int) $imgnub; ?><i>个</i></div>
            </div>
        </div>
    </div>
</div>

<div class="lg-panel-grid">
    <section class="lg-panel">
        <div class="lg-panel-head">
            <h4>最近访问（精确到市级）</h4>
            <a href="/admin/VisitLog.php" class="btn btn-sm btn-outline-primary">查看全部</a>
        </div>
        <div class="lg-panel-body table-responsive">
            <table class="table table-centered mb-0">
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>IP</th>
                        <th>国家</th>
                        <th>省份</th>
                        <th>城市</th>
                        <th>页面</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$recentVisits): ?>
                        <tr>
                            <td colspan="6"><div class="lg-empty">暂无访问记录，访客打开前台后会自动统计</div></td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($recentVisits as $visit): ?>
                        <tr>
                            <td>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars(date('m-d H:i', (int) $visit['visit_time']), ENT_QUOTES, 'UTF-8'); ?>
                                </small>
                            </td>
                            <td><span class="lg-ip"><?php echo htmlspecialchars((string) $visit['ip'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <td><?php echo htmlspecialchars((string) ($visit['country'] ?: '未知'), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) ($visit['province'] ?: '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) ($visit['city'] ?: '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-truncate" style="max-width: 160px;">
                                <?php echo htmlspecialchars((string) $visit['page'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="lg-panel">
        <div class="lg-panel-head">
            <h4>最近登录</h4>
            <a href="/admin/LoginInfo.php" class="btn btn-sm btn-outline-dark">登录行为</a>
        </div>
        <div class="lg-panel-body table-responsive">
            <table class="table table-centered mb-0">
                <thead>
                    <tr>
                        <th>管理员</th>
                        <th>时间</th>
                        <th>归属地</th>
                        <th>设备</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $loginRows = 0;
                    while ($info = mysqli_fetch_array($resBehavior)) {
                        $loginRows++;
                        ?>
                        <tr>
                            <td>
                                <span class="badge badge-success-lighten">
                                    <i class="mdi mdi-account-circle mr-1"></i>
                                    <?php echo htmlspecialchars((string) $info['L_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars(date('m-d H:i', (int) $info['L_date']), ENT_QUOTES, 'UTF-8'); ?>
                                    <div><?php echo htmlspecialchars(time_tran($info['L_date']) . '登录', ENT_QUOTES, 'UTF-8'); ?></div>
                                </small>
                            </td>
                            <td><?php echo htmlspecialchars((string) $info['L_city'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <small><?php echo GetOsIcon($info['L_os']) . ' ' . htmlspecialchars((string) $info['L_os'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </td>
                        </tr>
                        <?php
                    }
                    if ($loginRows === 0) {
                        echo '<tr><td colspan="4"><div class="lg-empty">暂无登录记录</div></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php
include_once 'Footer.php';
?>

</body>

</html>
