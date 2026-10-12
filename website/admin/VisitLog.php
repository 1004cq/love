<?php
session_start();
include_once 'Nav.php';
require_once __DIR__ . '/VisitStats.php';

$visitStats = lg_visit_dashboard_stats($connect);
$visits = lg_visit_recent($connect, 100);
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted mt-0">今日访问人数</h5>
                <h3><?php echo (int) $visitStats['today']; ?> <small class="text-muted">人</small></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted mt-0">昨日访问人数</h5>
                <h3><?php echo (int) $visitStats['yesterday']; ?> <small class="text-muted">人</small></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted mt-0">总访问人数</h5>
                <h3><?php echo (int) $visitStats['total']; ?> <small class="text-muted">人</small></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">访问明细（精确到市级）</h4>
                <div class="table-responsive">
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
                                    <td><code><?php echo htmlspecialchars((string) $visit['ip'], ENT_QUOTES, 'UTF-8'); ?></code></td>
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
                                echo '<tr><td colspan="10" class="text-muted">暂无访问记录</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'Footer.php'; ?>
</body>
</html>
