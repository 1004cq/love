<?php
session_start();
include_once 'Nav.php';
include_once 'connect.php';
$ipkiki = "select * from IPerror order by id desc";
$ipki = mysqli_query($connect, $ipkiki);
$IpData_row = mysqli_num_rows($ipki);
?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">IP封禁管理
                    <a class="fabu" href="/admin/ipSet.php">
                        <button type="button" class="btn btn-success btn-sm right_10">
                            <i class="mdi mdi-circle-edit-outline"></i>新增
                        </button>
                    </a>
                </h4>

                <?php if ($IpData_row <= 0): ?>
                    <div class="NoDataCard-warp">
                        <div class="NoDataCard">
                            <img src="../Style/img/NoData.svg" alt="">
                        </div>
                        <div class="NoDataInfo">
                            <h3>当前页面暂无数据！</h3>
                        </div>
                    </div>
                <?php endif ?>

                <div class="<?php echo ($IpData_row <= 0) ? ' HiddenElements' : '' ?>">
                    <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>IP/归属地</th>
                                <th>备注</th>
                                <th>Date</th>
                                <th style="width: 125px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($IPinfo = mysqli_fetch_array($ipki)) {
                                $num_i = $num_i + 1;
                            ?>
                                <tr>
                                    <td><span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                            <i class="ri-at-line rihjt-0"></i>
                                            <?php echo $num_i ?>
                                        </span></td>
                                    <td>
                                        <h5>
                                            <span
                                                class="badge badge-danger-lighten"><?php if ($IPinfo['State']) { ?><?php echo $IPinfo['State'] ?><?php } else { ?>127.0.0.1<?php } ?></span>
                                            <span class="badge badge-warning-lighten">
                                                <?php if ($IPinfo['ipAdd']) {
                                                    echo $IPinfo['ipAdd'];
                                                } ?>
                                            </span>
                                        </h5>
                                    </td>
                                    <td>
                                        <h5><?php echo $IPinfo['text'] ?></h5>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo $IPinfo['Time'] ?></small>
                                    </td>
                                    <td>
                                        <a onclick="PublicPopUpLayer(1,'IPerror','<?php echo htmlspecialchars(json_encode($IPinfo, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
                                            <button style="white-space: nowrap;" type="button"
                                                class="btn btn-danger btn-rounded">
                                                <i class=" mdi mdi-delete-empty mr-1"></i>删除
                                            </button>
                                        </a>
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


<script>
    function del(id, State) {
        if (confirm('您确认要删除IP为 ' + State + ' 吗')) {
            location.href = 'delip.php?id=' + id + '&State' + State;
        }
    }
</script>


<?php
include_once 'Footer.php';
?>

</body>

</html>