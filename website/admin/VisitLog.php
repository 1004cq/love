<?php
session_start();
include_once 'Nav.php';
require_once __DIR__ . '/VisitStats.php';

$visitStats = lg_visit_dashboard_stats($connect);
$visits = lg_visit_recent($connect, 100);
?>

<div class="lg-admin-page-head">
    <div>
        <h2>访问统计</h2>
        <p>按 IP 去重的访问人数，归属地精确到市级</p>
    </div>
    <div>
        <a href="/admin/index.php" class="btn btn-sm btn-outline-dark">返回仪表盘</a>
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

<section class="lg-panel lg-admin-section">
    <div class="lg-panel-head">
        <h4>访问明细</h4>
    </div>
    <div class="lg-panel-body table-responsive">
        <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>时间</th>
                    <th>访问 IP</th>
                    <th>国家</th>
                    <th>省份</th>
                    <th>城市</th>
                    <th>完整归属地</th>
                    <th>方法</th>
                    <th>页面</th>
                    <th>来源</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $num = 0;
                foreach ($visits as $visit) {
                    $num++;
                    $fullLabel = trim(implode(' · ', array_filter([
                        (string) ($visit['country'] ?? ''),
                        (string) ($visit['province'] ?? ''),
                        (string) ($visit['city'] ?? ''),
                    ], static function ($v) {
                        return $v !== '' && $v !== '未知';
                    })), ' ·');
                    if ($fullLabel === '') {
                        $fullLabel = (string) ($visit['region'] ?: '未知');
                    }
                    ?>
                    <tr>
                        <td><?php echo $num; ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', (int) $visit['visit_time']), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><span class="lg-ip"><?php echo htmlspecialchars((string) $visit['ip'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><?php echo htmlspecialchars((string) ($visit['country'] ?: '未知'), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($visit['province'] ?: '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($visit['city'] ?: '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($fullLabel, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) $visit['method'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-truncate" style="max-width: 240px;"><?php echo htmlspecialchars((string) $visit['page'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-truncate" style="max-width: 180px;"><?php echo htmlspecialchars((string) $visit['referer'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php
                }
                if ($num === 0) {
                    echo '<tr><td colspan="10"><div class="lg-empty">暂无访问记录</div></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php include_once 'Footer.php'; ?>
</body>
</html>
