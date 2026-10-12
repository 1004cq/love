<?php
session_start();
include_once 'Nav.php';
$ipkiki = "select * from warning order by id desc";
$ipki = mysqli_query($connect, $ipkiki);
$FeifaData_row = mysqli_num_rows($ipki);
?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">非法访问列表</h4>

                <?php if ($FeifaData_row <= 0): ?>
                    <div class="NoDataCard-warp">
                        <div class="NoDataCard">
                            <img src="../Style/img/NoData.svg" alt="">
                        </div>
                        <div class="NoDataInfo">
                            <h3>当前页面暂无数据！</h3>
                        </div>
                    </div>
                <?php endif ?>

                <div class="<?php echo ($FeifaData_row <= 0) ? ' HiddenElements' : '' ?>">
                    <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>非法文件路径</th>
                                <th>IP/归属地</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($IPinfo = mysqli_fetch_array($ipki)) {
                                $num_i = $num_i + 1;
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                            <i class="ri-at-line rihjt-0"></i>
                                            <?php echo $num_i ?>
                                        </span>
                                    </td>
                                    <td>
                                        <h5><span class="badge badge-success-lighten F_title" data-toggle="tooltip"
                                                data-placement="top" title=""
                                                data-original-title="<?php echo htmlspecialchars($IPinfo['file'], ENT_QUOTES, 'UTF-8') ?>">
                                                <?php echo htmlspecialchars($IPinfo['file'], ENT_QUOTES, 'UTF-8') ?></span></h5>
                                    </td>
                                    <td>
                                        <h5>
                                            <span
                                                class="badge badge-danger-lighten"><?php if ($IPinfo['ip']) { ?><?php echo $IPinfo['ip'] ?><?php } else { ?>127.0.0.1<?php } ?></span>
                                            <span class="badge badge-warning-lighten">
                                                <?php if ($IPinfo['gsd']) {
                                                    echo $IPinfo['gsd'];
                                                } ?>
                                            </span>
                                        </h5>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo $IPinfo['time'] ?></small>
                                    </td>
                                    <td>
                                        <button id="BanIpInfo" data-ipinfo='<?php echo htmlspecialchars(json_encode($IPinfo, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>' type="button" class="btn btn-danger btn-rounded"
                                            onclick="PublicPopUpLayer(5, 2, this.dataset.ipinfo)">
                                            <i class="mdi mdi-backspace mr-1"></i>
                                            封禁IP
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<?php
include_once 'Footer.php';
?>

</body>

</html>