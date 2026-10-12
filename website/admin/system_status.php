<?php
require_once __DIR__ . '/Nav.php';

$extensions = ['mysqli', 'pdo_mysql', 'mbstring', 'json', 'curl', 'gd', 'exif', 'fileinfo'];
$mysqlVersion = '无法读取';
$versionResult = $conn->query('SELECT VERSION() AS version');
if ($versionResult && ($versionRow = $versionResult->fetch_assoc())) {
    $mysqlVersion = $versionRow['version'];
}
$checks = [
    'PHP 8.2–8.5' => version_compare(PHP_VERSION, '8.2', '>=') && version_compare(PHP_VERSION, '8.6', '<'),
    'MySQL 5.7+' => version_compare($mysqlVersion, '5.7', '>='),
    '上传目录可写' => is_writable(dirname(__DIR__) . '/Lovefolder'),
    'API 目录可写' => is_writable(dirname(__DIR__) . '/api'),
    '安装锁存在' => file_exists(dirname(__DIR__) . '/install/install.lock'),
];
?>
<div class="row">
  <div class="col-12">
    <div class="card"><div class="card-body">
      <h4 class="header-title mb-3">系统状态与兼容性检查</h4>
      <p class="text-muted">升级版 3.1 · PHP <?php echo htmlspecialchars(PHP_VERSION); ?> · MySQL <?php echo htmlspecialchars($mysqlVersion); ?></p>
      <div class="row">
        <?php foreach ($checks as $label => $ok): ?>
          <div class="col-md-6 mb-2"><div class="alert alert-<?php echo $ok ? 'success' : 'danger'; ?> mb-0">
            <?php echo $ok ? '✓' : '×'; ?> <?php echo htmlspecialchars($label); ?>
          </div></div>
        <?php endforeach; ?>
      </div>
      <hr>
      <h5>PHP 扩展</h5>
      <div class="row">
        <?php foreach ($extensions as $extension): $loaded = extension_loaded($extension); ?>
          <div class="col-sm-6 col-lg-3 mb-2"><span class="badge badge-<?php echo $loaded ? 'success' : 'danger'; ?> p-2">
            <?php echo htmlspecialchars($extension); ?>: <?php echo $loaded ? '已启用' : '缺失'; ?>
          </span></div>
        <?php endforeach; ?>
      </div>
      <p class="mt-3 mb-0"><a href="/api/health.php" target="_blank">打开公开健康检查接口</a></p>
    </div></div>
  </div>
</div>
<?php require_once __DIR__ . '/Footer.php'; ?>
</body></html>
